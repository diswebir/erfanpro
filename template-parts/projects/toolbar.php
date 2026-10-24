<?php
/**
 * Phase 6: projects filter toolbar + grid.
 *
 * Two filter rows exactly like the reference archive: city chips
 * (rounded-xl, «همه شهرها») and project_type pills (rounded-full,
 * «همه»). The initial render runs the default query server-side;
 * every interaction re-renders through admin-ajax (assets/js/projects.js).
 *
 * @package Erfanpro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* Seed the initial render from the archive context: current city or
 * project_type on taxonomy archives, the page number on paged views. */
$erfanpro_proj_seed = array(
	'paged' => get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1,
);
if ( is_tax( 'city' ) || is_tax( 'project_type' ) ) {
	$erfanpro_qo = get_queried_object();
	if ( $erfanpro_qo instanceof WP_Term ) {
		if ( 'city' === $erfanpro_qo->taxonomy ) {
			$erfanpro_proj_seed['city'] = $erfanpro_qo->slug;
		} else {
			$erfanpro_proj_seed['type'] = $erfanpro_qo->slug;
		}
	}
}
$erfanpro_proj_params = erfanpro_projects_sanitize_params( $erfanpro_proj_seed );
$erfanpro_proj_result = erfanpro_projects_render( $erfanpro_proj_params );

$erfanpro_city_chip_active   = 'rounded-xl px-3.5 py-1.5 text-xs font-bold transition-all bg-emerald-800 text-white shadow-xs';
$erfanpro_city_chip_inactive = 'rounded-xl px-3.5 py-1.5 text-xs font-bold transition-all border border-slate-200 bg-white text-slate-600 hover:border-emerald-300 hover:text-emerald-700';
$erfanpro_type_pill_active   = 'rounded-full px-5 py-2.5 text-[13.5px] font-bold transition-all duration-300 bg-emerald-600 text-white shadow-md shadow-emerald-600/25';
$erfanpro_type_pill_inactive = 'rounded-full px-5 py-2.5 text-[13.5px] font-bold transition-all duration-300 border border-slate-200 bg-white text-slate-600 hover:border-emerald-400 hover:text-emerald-700';

$erfanpro_cities = get_terms(
	array(
		'taxonomy'   => 'city',
		'hide_empty' => true,
		'orderby'    => 'name',
		'order'      => 'ASC',
	)
);
if ( is_wp_error( $erfanpro_cities ) ) {
	$erfanpro_cities = array();
}
$erfanpro_types = get_terms(
	array(
		'taxonomy'   => 'project_type',
		'hide_empty' => true,
		'orderby'    => 'name',
		'order'      => 'ASC',
	)
);
if ( is_wp_error( $erfanpro_types ) ) {
	$erfanpro_types = array();
}

$erfanpro_proj_shown = 0;
if ( $erfanpro_proj_result['total'] > 0 ) {
	$erfanpro_proj_shown = min( $erfanpro_proj_result['total'], ERFANPRO_PROJECTS_PER_PAGE * $erfanpro_proj_params['paged'] ) - ERFANPRO_PROJECTS_PER_PAGE * ( $erfanpro_proj_params['paged'] - 1 );
	$erfanpro_proj_shown = max( 0, $erfanpro_proj_shown );
}
?>
<section id="archive" class="mx-auto max-w-7xl scroll-mt-24 px-5 py-16 lg:px-8 lg:py-20" data-erfanpro-projects data-initial-city="<?php echo esc_attr( $erfanpro_proj_params['city'] ); ?>" data-initial-type="<?php echo esc_attr( $erfanpro_proj_params['type'] ); ?>">
	<div class="mb-6 flex flex-wrap items-center gap-2" data-projects-city-row<?php erfanpro_reveal( 0.00, 'inview', null, 'opacity: 1; filter: blur(0px); transform: none;' ); ?>>
		<button type="button" data-projects-city="" class="<?php echo esc_attr( '' === $erfanpro_proj_params['city'] ? $erfanpro_city_chip_active : $erfanpro_city_chip_inactive ); ?>"><?php esc_html_e( 'همه شهرها', 'erfanpro' ); ?></button>
		<?php foreach ( $erfanpro_cities as $term ) : ?>
		<button type="button" data-projects-city="<?php echo esc_attr( $term->slug ); ?>" class="<?php echo esc_attr( $term->slug === $erfanpro_proj_params['city'] ? $erfanpro_city_chip_active : $erfanpro_city_chip_inactive ); ?>"><?php echo esc_html( $term->name ); ?></button>
		<?php endforeach; ?>
	</div>

	<div class="mb-8 flex flex-wrap items-center gap-2.5" data-projects-type-row<?php erfanpro_reveal( 0.04, 'inview', null, 'opacity: 1; filter: blur(0px); transform: none;' ); ?>>
		<button type="button" data-projects-type="" class="<?php echo esc_attr( '' === $erfanpro_proj_params['type'] ? $erfanpro_type_pill_active : $erfanpro_type_pill_inactive ); ?>"><?php esc_html_e( 'همه', 'erfanpro' ); ?></button>
		<?php foreach ( $erfanpro_types as $term ) : ?>
		<button type="button" data-projects-type="<?php echo esc_attr( $term->slug ); ?>" class="<?php echo esc_attr( $term->slug === $erfanpro_proj_params['type'] ? $erfanpro_type_pill_active : $erfanpro_type_pill_inactive ); ?>"><?php echo esc_html( $term->name ); ?></button>
		<?php endforeach; ?>
	</div>

	<p id="erfanpro-projects-count" class="mb-5 text-center text-[13px] font-bold text-slate-500"><?php echo esc_html( erfanpro_projects_count_text( $erfanpro_proj_shown, $erfanpro_proj_result['total'] ) ); ?></p>

	<div id="erfanpro-projects-grid" class="grid gap-7 sm:grid-cols-2 lg:grid-cols-3" aria-live="polite">
		<?php echo $erfanpro_proj_result['html']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	</div>

	<div id="erfanpro-projects-pagination">
		<?php echo erfanpro_projects_pagination_html( $erfanpro_proj_result['max_pages'], $erfanpro_proj_params['paged'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	</div>
</section>
