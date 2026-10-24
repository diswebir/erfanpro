<?php
/**
 * Phase 8: blog — archive helpers, blog-scoped AJAX search,
 * single-post helpers (reading time, Jalali date, TOC, related posts).
 *
 * Conventions (decision 12 — real data only):
 * - categories come from the real `category` taxonomy;
 * - dates render in Jalali (like the reference: «۱۲ مرداد ۱۴۰۴»);
 * - reading time is computed from the actual word count;
 * - the editor's pick is the latest published post (the reference
 *   shows the newest article as both «منتخب سردبیر» and the first
 *   «ویژه» grid card).
 *
 * The global site-wide search page is phase 9; the search here is
 * scoped to posts only (AJAX, debounced, like phase 4's shop search).
 *
 * @package Erfanpro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'ERFANPRO_BLOG_PER_PAGE', 9 );

/* ------------------------------------------------------------------ */
/* Jalali date + reading time                                         */
/* ------------------------------------------------------------------ */

/**
 * Persian month names (Jalali).
 *
 * @return string[]
 */
function erfanpro_jalali_months() {
	return array(
		1  => 'فروردین',
		2  => 'اردیبهشت',
		3  => 'خرداد',
		4  => 'تیر',
		5  => 'مرداد',
		6  => 'شهریور',
		7  => 'مهر',
		8  => 'آبان',
		9  => 'آذر',
		10 => 'دی',
		11 => 'بهمن',
		12 => 'اسفند',
	);
}

/**
 * Convert a Unix timestamp to a Jalali «day month year» date string
 * with Persian digits, e.g. «۱۲ مرداد ۱۴۰۴».
 *
 * @param int $timestamp Unix timestamp.
 * @return string
 */
function erfanpro_jalali_date( $timestamp ) {
	$gy = (int) date( 'Y', $timestamp );
	$gm = (int) date( 'n', $timestamp );
	$gd = (int) date( 'j', $timestamp );

	$g_days_in_month = array( 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31 );
	$j_days_in_month = array( 31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29 );

	$gy2 = $gy - 1600;
	$gm2 = $gm - 1;
	$gd2 = $gd - 1;

	$g_day_no = 365 * $gy2 + (int) floor( ( $gy2 + 3 ) / 4 ) - (int) floor( ( $gy2 + 99 ) / 100 ) + (int) floor( ( $gy2 + 399 ) / 400 );
	for ( $i = 0; $i < $gm2; ++$i ) {
		$g_day_no += $g_days_in_month[ $i ];
	}
	if ( $gm2 > 1 && ( ( 0 === $gy2 % 4 && 0 !== $gy2 % 100 ) || 0 === $gy2 % 400 ) ) {
		++$g_day_no;
	}
	$g_day_no += $gd2;

	$j_day_no = $g_day_no - 79;
	$j_np     = (int) floor( $j_day_no / 12053 );
	$j_day_no = $j_day_no % 12053;

	$jy       = 979 + 33 * $j_np + 4 * (int) floor( $j_day_no / 1461 );
	$j_day_no = $j_day_no % 1461;

	if ( $j_day_no >= 366 ) {
		$jy       += (int) floor( ( $j_day_no - 1 ) / 365 );
		$j_day_no  = ( $j_day_no - 1 ) % 365;
	}
	$jm = 1;
	for ( $i = 0; $i < 11 && $j_day_no >= $j_days_in_month[ $i ]; ++$i ) {
		$j_day_no -= $j_days_in_month[ $i ];
		$jm        = $i + 2;
	}
	$jd = $j_day_no + 1;

	$months = erfanpro_jalali_months();
	return erfanpro_fa_digits(
		sprintf( '%d %s %d', $jd, $months[ $jm ], $jy )
	);
}

/**
 * Jalali date of a post's publish time.
 *
 * @param int $post_id Post ID.
 * @return string
 */
function erfanpro_post_jalali_date( $post_id ) {
	return erfanpro_jalali_date( (int) get_post_time( 'U', false, $post_id ) );
}

/**
 * Estimated reading time in minutes (200 wpm, min 1).
 *
 * @param int $post_id Post ID.
 * @return int
 */
function erfanpro_reading_time_minutes( $post_id ) {
	$content = (string) get_post_field( 'post_content', $post_id );
	$text    = wp_strip_all_tags( strip_shortcodes( $content ) );
	$words   = preg_split( '/\s+/u', trim( $text ) );
	$count   = is_array( $words ) ? count( array_filter( $words ) ) : 0;
	return max( 1, (int) ceil( $count / 200 ) );
}

/**
 * Reading-time label, e.g. «۹ دقیقه مطالعه».
 *
 * @param int $post_id Post ID.
 * @return string
 */
function erfanpro_reading_time_label( $post_id ) {
	return sprintf(
		/* translators: reading time in minutes */
		__( '%s دقیقه مطالعه', 'erfanpro' ),
		erfanpro_fa_digits( erfanpro_reading_time_minutes( $post_id ) )
	);
}

/* ------------------------------------------------------------------ */
/* Archive data                                                       */
/* ------------------------------------------------------------------ */

/**
 * Real blog categories with post counts.
 *
 * @return array<int,array{slug:string,name:string,count:int}>
 */
function erfanpro_blog_categories() {
	$terms = get_terms(
		array(
			'taxonomy'   => 'category',
			'hide_empty' => true,
			'orderby'    => 'name',
			'order'      => 'ASC',
		)
	);
	if ( is_wp_error( $terms ) || empty( $terms ) ) {
		return array();
	}
	$out = array();
	foreach ( $terms as $term ) {
		$out[] = array(
			'slug'  => $term->slug,
			'name'  => $term->name,
			'count' => (int) $term->count,
		);
	}
	return $out;
}

/**
 * Total published post count.
 *
 * @return int
 */
function erfanpro_blog_total_count() {
	$counts = wp_count_posts( 'post' );
	return isset( $counts->publish ) ? (int) $counts->publish : 0;
}

/**
 * Editor's pick = the latest published post.
 *
 * @return WP_Post|null
 */
function erfanpro_blog_editors_pick() {
	$posts = get_posts(
		array(
			'post_type'      => 'post',
			'post_status'    => 'publish',
			'posts_per_page' => 1,
			'orderby'        => 'date',
			'order'          => 'DESC',
		)
	);
	return ! empty( $posts ) ? $posts[0] : null;
}

/**
 * URL of the blog posts index (the page set as «page for posts»,
 * falling back to /blog/).
 *
 * @return string
 */
function erfanpro_blog_index_url() {
	$page_id = (int) get_option( 'page_for_posts' );
	if ( $page_id > 0 ) {
		$url = get_permalink( $page_id );
		if ( $url ) {
			return $url;
		}
	}
	return home_url( '/blog/' );
}

/**
 * Sanitise the raw blog filter state (from $_POST or page load).
 *
 * @param array $raw Raw input.
 * @return array{cat:string,s:string,paged:int}
 */
function erfanpro_blog_sanitize_params( $raw ) {
	$cat = isset( $raw['cat'] ) ? sanitize_text_field( (string) $raw['cat'] ) : '';
	if ( '' !== $cat && ! term_exists( $cat, 'category' ) ) {
		$cat = '';
	}

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
		'cat'   => $cat,
		's'     => $s,
		'paged' => $paged,
	);
}

/**
 * Build WP_Query args from the sanitised blog filter state.
 *
 * @param array $params Sanitised params (erfanpro_blog_sanitize_params).
 * @return array WP_Query args.
 */
function erfanpro_blog_query_args( $params ) {
	$args = array(
		'post_type'      => 'post',
		'post_status'    => 'publish',
		'posts_per_page' => ERFANPRO_BLOG_PER_PAGE,
		'paged'          => $params['paged'],
		'orderby'        => 'date',
		'order'          => 'DESC',
	);

	if ( '' !== $params['cat'] ) {
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'category',
				'field'    => 'slug',
				'terms'    => array( $params['cat'] ),
			),
		);
	}

	if ( '' !== $params['s'] ) {
		$args['s'] = $params['s'];
	}

	return $args;
}

/* ------------------------------------------------------------------ */
/* Cards                                                              */
/* ------------------------------------------------------------------ */

/**
 * One blog card — reference card-light layout:
 * image + category badge (+ «ویژه» on the first card), date + reading
 * time, title, excerpt, author + «ادامه مطلب» footer.
 *
 * @param int   $post_id  Post ID.
 * @param float $delay    Reveal stagger delay in seconds.
 * @param bool  $featured Show the «ویژه» badge (latest post).
 * @return string Card HTML.
 */
function erfanpro_blog_card_html( $post_id, $delay = 0.0, $featured = false ) {
	$post = get_post( $post_id );
	if ( ! $post || 'publish' !== $post->post_status ) {
		return '';
	}

	$title = get_the_title( $post_id );
	$link  = get_permalink( $post_id );

	$image = get_the_post_thumbnail_url( $post_id, 'large' );

	$cat_name = '';
	$cats     = get_the_category( $post_id );
	if ( ! is_wp_error( $cats ) && ! empty( $cats ) ) {
		$cat_name = $cats[0]->name;
	}

	$excerpt = has_excerpt( $post_id )
		? wp_strip_all_tags( get_the_excerpt( $post_id ) )
		: wp_trim_words( wp_strip_all_tags( strip_shortcodes( $post->post_content ) ), 28, '…' );

	$author = get_the_author_meta( 'display_name', (int) $post->post_author );
	if ( '' === trim( (string) $author ) ) {
		$author = __( 'کارشناسان فنی عرفان صنعت', 'erfanpro' );
	}

	$delay_s = number_format( (float) $delay, 2, '.', '' );

	ob_start();
	?>
	<div data-anim="" data-anim-init="opacity: 0; transform: translateY(42px); filter: blur(6px);" data-anim-final="opacity: 1; filter: blur(0px); transform: none;" data-anim-kind="inview" data-anim-delay="<?php echo esc_attr( $delay_s ); ?>" style="opacity: 0; transform: translateY(42px); filter: blur(6px); --anim-delay: <?php echo esc_attr( $delay_s ); ?>s;">
		<a class="card-light group flex h-full flex-col overflow-hidden rounded-[1.5rem]" href="<?php echo esc_url( $link ); ?>">
			<div class="relative h-52 overflow-hidden">
				<?php if ( $image ) : ?>
				<img alt="<?php echo esc_attr( $title ); ?>" loading="lazy" class="h-full w-full object-cover transition-transform duration-[1.1s] ease-[cubic-bezier(0.22,1,0.36,1)] group-hover:scale-110" src="<?php echo esc_url( $image ); ?>">
				<?php endif; ?>
				<?php if ( '' !== $cat_name ) : ?>
				<span class="absolute right-4 top-4 rounded-full bg-emerald-950/80 px-3.5 py-1.5 text-[11px] font-bold text-emerald-100 backdrop-blur-md"><?php echo esc_html( $cat_name ); ?></span>
				<?php endif; ?>
				<?php if ( $featured ) : ?>
				<span class="absolute left-4 top-4 rounded-full bg-emerald-600 px-3 py-1.5 text-[10.5px] font-bold text-white shadow-lg">★ <?php esc_html_e( 'ویژه', 'erfanpro' ); ?></span>
				<?php endif; ?>
				<div class="absolute inset-x-0 bottom-0 h-24 bg-gradient-to-t from-emerald-950/70 to-transparent"></div>
			</div>
			<div class="flex flex-1 flex-col gap-3.5 p-6">
				<div class="flex flex-wrap items-center gap-x-4 gap-y-1.5 text-[11.5px] font-semibold text-slate-500">
					<span class="flex items-center gap-1.5"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar-days h-3.5 w-3.5 text-emerald-600" aria-hidden="true"><path d="M8 2v3"></path><path d="M16 2v3"></path><rect x="3" y="3" width="18" height="18" rx="2"></rect><path d="M3 9h18"></path><path d="M8 13h.01"></path><path d="M12 13h.01"></path><path d="M16 13h.01"></path><path d="M8 17h.01"></path><path d="M12 17h.01"></path><path d="M16 17h.01"></path></svg><?php echo esc_html( erfanpro_post_jalali_date( $post_id ) ); ?></span>
					<span class="flex items-center gap-1.5"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clock-3 h-3.5 w-3.5 text-emerald-600" aria-hidden="true"><circle cx="12" cy="12" r="10"></circle><path d="M12 6v6h4"></path></svg><?php echo esc_html( erfanpro_reading_time_label( $post_id ) ); ?></span>
				</div>
				<h3 class="text-[16.5px] font-extrabold leading-8 text-slate-900 transition-colors group-hover:text-emerald-700"><?php echo esc_html( $title ); ?></h3>
				<?php if ( '' !== $excerpt ) : ?>
				<p class="line-clamp-3 text-[13px] leading-7 text-slate-600"><?php echo esc_html( $excerpt ); ?></p>
				<?php endif; ?>
				<div class="mt-auto flex items-center justify-between border-t border-slate-100 pt-4">
					<span class="flex items-center gap-1.5 text-[11.5px] font-semibold text-slate-500"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user-round lucide-user-2 h-3.5 w-3.5 text-emerald-600" aria-hidden="true"><circle cx="12" cy="8" r="5"></circle><path d="M20 21a8 8 0 0 0-16 0"></path></svg><?php echo esc_html( $author ); ?></span>
					<span class="flex items-center gap-1.5 text-[12.5px] font-bold text-emerald-700"><?php esc_html_e( 'ادامه مطلب', 'erfanpro' ); ?><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-up-left h-4 w-4 transition-transform duration-400 group-hover:-translate-y-0.5 group-hover:-translate-x-0.5" aria-hidden="true"><path d="M7 17V7h10"></path><path d="M17 17 7 7"></path></svg></span>
				</div>
			</div>
		</a>
	</div>
	<?php
	return (string) ob_get_clean();
}

/**
 * The «منتخب سردبیر» featured card — reference tilted image card with
 * gradient overlay, category + reading time meta, title, «مطالعه مقاله».
 *
 * @param int $post_id Post ID.
 * @return string Card HTML.
 */
function erfanpro_blog_editors_pick_html( $post_id ) {
	$post = get_post( $post_id );
	if ( ! $post || 'publish' !== $post->post_status ) {
		return '';
	}

	$title = get_the_title( $post_id );
	$link  = get_permalink( $post_id );
	$image = get_the_post_thumbnail_url( $post_id, 'large' );

	$cat_name = '';
	$cats     = get_the_category( $post_id );
	if ( ! is_wp_error( $cats ) && ! empty( $cats ) ) {
		$cat_name = $cats[0]->name;
	}

	ob_start();
	?>
	<div class="relative mx-auto w-full max-w-[30rem]"<?php erfanpro_reveal( 0.0, 'mount', 'opacity: 0; transform: translateY(34px) rotate(1.5deg);', 'opacity: 1; transform: rotate(1.5deg);' ); ?>>
		<div aria-hidden="true" class="absolute left-1/2 top-1/2 h-[70%] w-[70%] -translate-x-1/2 -translate-y-1/2 rounded-full bg-emerald-400/15 blur-[80px]"></div>
		<a class="group relative block overflow-hidden rounded-[2.5rem] border border-white/20 shadow-[0_40px_80px_-30px_rgba(0,0,0,0.7)]" href="<?php echo esc_url( $link ); ?>">
			<?php if ( $image ) : ?>
			<img alt="<?php echo esc_attr( $title ); ?>" class="h-[24rem] w-full object-cover transition-transform duration-[1.4s] group-hover:scale-105 sm:h-[27rem]" src="<?php echo esc_url( $image ); ?>">
			<?php endif; ?>
			<div class="absolute inset-0 bg-gradient-to-t from-emerald-950/85 via-emerald-950/25 to-transparent"></div>
			<span class="absolute right-5 top-5 rounded-full bg-amber-300 px-4 py-1.5 text-[11.5px] font-black text-emerald-950 shadow-lg">★ <?php esc_html_e( 'منتخب سردبیر', 'erfanpro' ); ?></span>
			<div class="absolute inset-x-0 bottom-0 p-6">
				<div class="mb-2.5 flex items-center gap-3 text-[11.5px] font-bold text-emerald-200">
					<?php if ( '' !== $cat_name ) : ?>
					<span><?php echo esc_html( $cat_name ); ?></span>
					<span class="h-1 w-1 rounded-full bg-white/50"></span>
					<?php endif; ?>
					<span class="flex items-center gap-1"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clock-3 h-3 w-3" aria-hidden="true"><circle cx="12" cy="12" r="10"></circle><path d="M12 6v6h4"></path></svg><?php echo esc_html( erfanpro_reading_time_label( $post_id ) ); ?></span>
				</div>
				<h3 class="line-clamp-2 text-[19px] font-black leading-8 text-white [text-shadow:0_2px_14px_rgba(0,0,0,0.6)]"><?php echo esc_html( $title ); ?></h3>
				<span class="mt-3 inline-flex items-center gap-2 rounded-full bg-white/15 px-4 py-2 text-[12.5px] font-extrabold text-white backdrop-blur transition-colors group-hover:bg-emerald-500"><?php esc_html_e( 'مطالعه مقاله', 'erfanpro' ); ?><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-up-left h-4 w-4" aria-hidden="true"><path d="M7 17V7h10"></path><path d="M17 17 7 7"></path></svg></span>
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
function erfanpro_blog_render( $params ) {
	$query = new WP_Query( erfanpro_blog_query_args( $params ) );

	$html = '';
	$i    = 0;
	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();
			$html .= erfanpro_blog_card_html( get_the_ID(), 0.07 * ( $i % 3 ), 0 === $i && 1 === $params['paged'] && '' === $params['cat'] && '' === $params['s'] );
			++$i;
		}
		wp_reset_postdata();
	}

	if ( '' === $html ) {
		$html = sprintf(
			'<div class="col-span-full flex flex-col items-center gap-4 rounded-[1.5rem] border border-dashed border-slate-300 bg-white/70 px-6 py-16 text-center"><span class="grid h-14 w-14 place-items-center rounded-full bg-emerald-50 text-emerald-700"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-newspaper h-6 w-6" aria-hidden="true"><path d="M15 18h-5"></path><path d="M18 14h-8"></path><path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-4 0v-9a2 2 0 0 1 2-2h2"></path><rect width="8" height="4" x="10" y="6" rx="1"></rect></svg></span><p class="text-[15px] font-extrabold text-slate-800">%s</p><p class="max-w-sm text-[13px] leading-7 text-slate-500">%s</p><button type="button" data-blog-reset class="rounded-full bg-emerald-600 px-6 py-2.5 text-[13px] font-bold text-white shadow-md shadow-emerald-600/25 transition-all hover:bg-emerald-700">%s</button></div>',
			esc_html__( 'مقاله‌ای با این مشخصات پیدا نشد', 'erfanpro' ),
			esc_html__( 'دسته‌بندی یا عبارت جستجو را تغییر دهید تا نتایج بیشتری ببینید.', 'erfanpro' ),
			esc_html__( 'حذف فیلترها', 'erfanpro' )
		);
	}

	return array(
		'html'      => $html,
		'total'     => (int) $query->found_posts,
		'max_pages' => (int) $query->max_num_pages,
	);
}

/**
 * Numbered pagination for the blog grid (AJAX-driven, href fallback).
 *
 * @param int $total_pages Total pages.
 * @param int $current     Current page.
 * @return string Pagination HTML (empty when a single page).
 */
function erfanpro_blog_pagination_html( $total_pages, $current ) {
	if ( $total_pages < 2 ) {
		return '';
	}

	$base_btn = 'grid h-10 min-w-10 place-items-center rounded-full px-3 text-[13px] font-bold transition-all duration-300 border ';
	$inactive = $base_btn . 'border-slate-200 bg-white text-slate-600 hover:border-emerald-400 hover:text-emerald-700';
	$active   = $base_btn . 'bg-emerald-600 text-white border-emerald-600 shadow-md shadow-emerald-600/25';

	ob_start();
	?>
	<nav class="mt-10 flex items-center justify-center gap-2" aria-label="<?php esc_attr_e( 'صفحه‌بندی مقالات', 'erfanpro' ); ?>">
		<?php if ( $current > 1 ) : ?>
		<a href="<?php echo esc_url( get_pagenum_link( $current - 1 ) ); ?>" data-blog-page="<?php echo esc_attr( $current - 1 ); ?>" class="<?php echo esc_attr( $inactive ); ?>" aria-label="<?php esc_attr_e( 'صفحه قبلی', 'erfanpro' ); ?>"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-right h-4 w-4" aria-hidden="true"><path d="m9 18 6-6-6-6"></path></svg></a>
		<?php endif; ?>
		<?php for ( $i = 1; $i <= $total_pages; $i++ ) : ?>
		<a href="<?php echo esc_url( get_pagenum_link( $i ) ); ?>" data-blog-page="<?php echo esc_attr( $i ); ?>" class="<?php echo esc_attr( $i === $current ? $active : $inactive ); ?>"<?php echo $i === $current ? ' aria-current="page"' : ''; ?>><?php echo esc_html( erfanpro_fa_digits( $i ) ); ?></a>
		<?php endfor; ?>
		<?php if ( $current < $total_pages ) : ?>
		<a href="<?php echo esc_url( get_pagenum_link( $current + 1 ) ); ?>" data-blog-page="<?php echo esc_attr( $current + 1 ); ?>" class="<?php echo esc_attr( $inactive ); ?>" aria-label="<?php esc_attr_e( 'صفحه بعدی', 'erfanpro' ); ?>"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-left h-4 w-4" aria-hidden="true"><path d="m15 18-6-6 6-6"></path></svg></a>
		<?php endif; ?>
	</nav>
	<?php
	return (string) ob_get_clean();
}

/**
 * Result-count text: «نمایش X از Y مقاله».
 *
 * @param int $shown Shown on this page.
 * @param int $total Total found.
 * @return string
 */
function erfanpro_blog_count_text( $shown, $total ) {
	return sprintf(
		/* translators: 1: shown count, 2: total count */
		__( 'نمایش %1$s از %2$s مقاله', 'erfanpro' ),
		erfanpro_fa_digits( $shown ),
		erfanpro_fa_digits( $total )
	);
}

/**
 * AJAX handler: blog category filter + live search + pagination.
 * Scoped to posts only (the site-wide search page is phase 9).
 */
function erfanpro_blog_ajax_search() {
	check_ajax_referer( 'erfanpro-blog-search', 'nonce' );

	$params = erfanpro_blog_sanitize_params( wp_unslash( $_POST ) );
	$result = erfanpro_blog_render( $params );

	$shown = 0;
	if ( $result['total'] > 0 ) {
		$shown = min( $result['total'], ERFANPRO_BLOG_PER_PAGE * $params['paged'] ) - ERFANPRO_BLOG_PER_PAGE * ( $params['paged'] - 1 );
		$shown = max( 0, $shown );
	}

	wp_send_json_success(
		array(
			'html'       => $result['html'],
			'pagination' => erfanpro_blog_pagination_html( $result['max_pages'], $params['paged'] ),
			'count_text' => erfanpro_blog_count_text( $shown, $result['total'] ),
			'total'      => $result['total'],
			'paged'      => $params['paged'],
			'max_pages'  => $result['max_pages'],
		)
	);
}
add_action( 'wp_ajax_erfanpro_blog_search', 'erfanpro_blog_ajax_search' );
add_action( 'wp_ajax_nopriv_erfanpro_blog_search', 'erfanpro_blog_ajax_search' );

/* ------------------------------------------------------------------ */
/* Single post helpers                                                */
/* ------------------------------------------------------------------ */

/**
 * Related posts: same-category first, then latest, excluding the
 * current post.
 *
 * @param int $post_id Post ID.
 * @param int $count   How many.
 * @return WP_Post[]
 */
function erfanpro_related_posts( $post_id, $count = 3 ) {
	$found = array();

	$cats = get_the_category( $post_id );
	if ( ! is_wp_error( $cats ) && ! empty( $cats ) ) {
		$same = get_posts(
			array(
				'post_type'      => 'post',
				'post_status'    => 'publish',
				'posts_per_page' => $count,
				'post__not_in'   => array( $post_id ),
				'category__in'   => array( $cats[0]->term_id ),
				'orderby'        => 'date',
				'order'          => 'DESC',
			)
		);
		$found = $same;
	}

	if ( count( $found ) < $count ) {
		$exclude = array_merge( array( $post_id ), wp_list_pluck( $found, 'ID' ) );
		$latest  = get_posts(
			array(
				'post_type'      => 'post',
				'post_status'    => 'publish',
				'posts_per_page' => $count - count( $found ),
				'post__not_in'   => $exclude,
				'orderby'        => 'date',
				'order'          => 'DESC',
			)
		);
		$found = array_merge( $found, $latest );
	}

	return $found;
}

/**
 * Build the «سرفصل‌های مقاله» TOC from the content's h2 headings:
 * injects erfanpro-sec-N ids and returns the items.
 *
 * @param string $content Post content HTML.
 * @return array{content:string,items:array<int,array{id:string,text:string}>}
 */
function erfanpro_post_toc( $content ) {
	$items = array();

	if ( '' === trim( $content ) || ! class_exists( 'DOMDocument' ) ) {
		return array(
			'content' => $content,
			'items'   => $items,
		);
	}

	$dom = new DOMDocument( '1.0', 'UTF-8' );
	libxml_use_internal_errors( true );
	$dom->loadHTML( '<?xml encoding="UTF-8">' . $content, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD );
	libxml_clear_errors();

	$n = 0;
	foreach ( $dom->getElementsByTagName( 'h2' ) as $h2 ) {
		++$n;
		$id = 'erfanpro-sec-' . $n;
		$h2->setAttribute( 'id', $id );
		/* Anchor offset like the reference's scroll-mt-24 wrapper. */
		$existing = $h2->getAttribute( 'class' );
		$h2->setAttribute( 'class', trim( $existing . ' scroll-mt-24' ) );
		$text = trim( $h2->textContent );
		if ( '' !== $text ) {
			$items[] = array(
				'id'   => $id,
				'text' => $text,
			);
		}
	}

	$new_content = $dom->saveHTML();
	if ( false === $new_content ) {
		$new_content = $content;
	}
	$new_content = preg_replace( '/^<\?xml[^?]*\?>\s*/', '', (string) $new_content );

	return array(
		'content' => (string) $new_content,
		'items'   => $items,
	);
}
