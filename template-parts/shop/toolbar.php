<?php
/**
 * Phase 4: shop filter toolbar + product grid.
 *
 * - Warranty strip (reference).
 * - Category pills («همه» + live product_cat terms + «فقط قابل خرید»).
 * - Live AJAX search (decision 20, JetSearch-grade) + Persian sort
 *   select including «قابل خرید» (decision 14).
 * - Collapsible pa_* attribute panel (18 blueprint attributes).
 * - Result count, AJAX grid, numbered pagination.
 *
 * The initial render runs the default query server-side; every
 * interaction re-renders through admin-ajax (assets/js/shop.js).
 *
 * @package Erfanpro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* Seed the initial render from the archive context: current product
 * category on taxonomy archives, the search term on product search. */
$erfanpro_shop_seed = array(
	'paged' => get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1,
);
if ( function_exists( 'is_product_category' ) && is_product_category() ) {
	$erfanpro_qo = get_queried_object();
	if ( $erfanpro_qo instanceof WP_Term ) {
		$erfanpro_shop_seed['cat'] = $erfanpro_qo->slug;
	}
}
if ( is_search() ) {
	$erfanpro_shop_seed['s'] = get_search_query();
}
$erfanpro_shop_params = erfanpro_shop_sanitize_params( $erfanpro_shop_seed );
$erfanpro_shop_result = erfanpro_shop_render( $erfanpro_shop_params );

$erfanpro_tb_pill_active   = 'rounded-full px-5 py-2.5 text-[13px] font-bold transition-all duration-300 bg-emerald-600 text-white shadow-md shadow-emerald-600/25';
$erfanpro_tb_pill_inactive = 'rounded-full px-5 py-2.5 text-[13px] font-bold transition-all duration-300 border border-slate-200 bg-white text-slate-600 hover:border-emerald-400 hover:text-emerald-700';
$erfanpro_purch_active     = 'flex items-center gap-2 rounded-full px-5 py-2.5 text-[13px] font-bold transition-all duration-300 bg-emerald-600 text-white shadow-md shadow-emerald-600/25';
$erfanpro_purch_inactive   = 'flex items-center gap-2 rounded-full px-5 py-2.5 text-[13px] font-bold transition-all duration-300 border border-emerald-200 bg-white text-emerald-700 hover:bg-emerald-50';

$erfanpro_tb_cats = get_terms(
	array(
		'taxonomy'   => 'product_cat',
		'hide_empty' => true,
		'orderby'    => 'name',
		'order'      => 'ASC',
	)
);
if ( is_wp_error( $erfanpro_tb_cats ) ) {
	$erfanpro_tb_cats = array();
}

$erfanpro_tb_attrs = erfanpro_shop_attributes();
$erfanpro_tb_sort  = erfanpro_shop_sort_options();

$erfanpro_shown = 0;
if ( $erfanpro_shop_result['total'] > 0 ) {
	$erfanpro_shown = min( $erfanpro_shop_result['total'], ERFANPRO_SHOP_PER_PAGE * $erfanpro_shop_params['paged'] ) - ERFANPRO_SHOP_PER_PAGE * ( $erfanpro_shop_params['paged'] - 1 );
	$erfanpro_shown = max( 0, $erfanpro_shown );
}
?>
<section class="mx-auto max-w-7xl px-5 py-16 lg:px-8 lg:py-20" id="erfanpro-shop" data-initial-cat="<?php echo esc_attr( $erfanpro_shop_params['cat'] ); ?>">
	<div class="mb-10"<?php erfanpro_reveal( 0.00, 'inview', null, 'opacity: 1; filter: blur(0px); transform: none;' ); ?>>
		<div class="flex flex-wrap items-center justify-center gap-x-8 gap-y-3 rounded-2xl border border-emerald-100 bg-emerald-50/70 px-6 py-4 text-[13px] font-bold text-emerald-900">
			<?php
			$erfanpro_strip = array( '۲۴ ماه گارانتی تعویض', '۵ سال خدمات پس از فروش', 'استاندارد IP67 / IP68', 'خرید مستقیم از کارخانه' );
			foreach ( $erfanpro_strip as $item ) :
			?>
			<span class="flex items-center gap-2"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shield-check h-4.5 w-4.5 text-emerald-600" aria-hidden="true"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"></path><path d="m9 12 2 2 4-4"></path></svg><?php echo esc_html( $item ); ?></span>
			<?php endforeach; ?>
		</div>
	</div>

	<div class="mb-6 flex flex-col gap-3 lg:flex-row lg:items-center"<?php erfanpro_reveal( 0.04, 'inview', null, 'opacity: 1; filter: blur(0px); transform: none;' ); ?>>
		<div class="relative flex-1">
			<input type="search" id="erfanpro-shop-search" autocomplete="off" value="<?php echo esc_attr( $erfanpro_shop_params['s'] ); ?>" placeholder="<?php esc_attr_e( 'جستجو در محصولات…', 'erfanpro' ); ?>" class="w-full rounded-full border border-slate-200 bg-white py-2.5 pl-11 pr-5 text-[13.5px] font-medium text-slate-800 placeholder:text-slate-400 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
			<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search pointer-events-none absolute left-4 top-1/2 h-4.5 w-4.5 -translate-y-1/2 text-slate-400" aria-hidden="true"><circle cx="11" cy="11" r="8"></circle><path d="m21 21-4.3-4.3"></path></svg>
		</div>
		<div class="flex items-center gap-3">
			<label class="flex items-center gap-2 text-[13px] font-bold text-slate-600" for="erfanpro-shop-sort">
				<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-down-wide-narrow h-4 w-4 text-emerald-600" aria-hidden="true"><path d="m3 16 4 4 4-4"></path><path d="M7 20V4"></path><path d="M11 4h4"></path><path d="M11 8h7"></path><path d="M11 12h10"></path></svg>
				<?php esc_html_e( 'مرتب‌سازی:', 'erfanpro' ); ?>
			</label>
			<select id="erfanpro-shop-sort" class="rounded-full border border-slate-200 bg-white px-4 py-2.5 text-[13px] font-bold text-slate-700 focus:border-emerald-500 focus:outline-none">
				<?php foreach ( $erfanpro_tb_sort as $value => $label ) : ?>
				<option value="<?php echo esc_attr( $value ); ?>"<?php selected( $erfanpro_shop_params['sort'], $value ); ?>><?php echo esc_html( $label ); ?></option>
				<?php endforeach; ?>
			</select>
			<?php if ( ! empty( $erfanpro_tb_attrs ) ) : ?>
			<button type="button" id="erfanpro-shop-attrs-toggle" aria-expanded="false" class="flex items-center gap-2 rounded-full border border-slate-200 bg-white px-4 py-2.5 text-[13px] font-bold text-slate-700 transition-all hover:border-emerald-400 hover:text-emerald-700">
				<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-sliders-horizontal h-4 w-4 text-emerald-600" aria-hidden="true"><line x1="21" x2="14" y1="4" y2="4"></line><line x1="10" x2="3" y1="4" y2="4"></line><line x1="21" x2="12" y1="12" y2="12"></line><line x1="8" x2="3" y1="12" y2="12"></line><line x1="21" x2="16" y1="20" y2="20"></line><line x1="12" x2="3" y1="20" y2="20"></line><line x1="14" x2="14" y1="2" y2="6"></line><line x1="8" x2="8" y1="10" y2="14"></line><line x1="16" x2="16" y1="18" y2="22"></line></svg>
				<?php esc_html_e( 'فیلتر ویژگی‌ها', 'erfanpro' ); ?>
			</button>
			<?php endif; ?>
		</div>
	</div>

	<?php if ( ! empty( $erfanpro_tb_attrs ) ) : ?>
	<div id="erfanpro-shop-attrs" class="mb-6 hidden rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
		<?php foreach ( $erfanpro_tb_attrs as $group ) : ?>
		<div class="mb-4 last:mb-0" data-attr-group="<?php echo esc_attr( $group['taxonomy'] ); ?>">
			<p class="mb-2 text-[13px] font-extrabold text-slate-800"><?php echo esc_html( $group['label'] ); ?></p>
			<div class="flex flex-wrap gap-2">
				<?php foreach ( $group['terms'] as $term ) : ?>
				<label class="cursor-pointer">
					<input type="checkbox" class="sr-only" data-attr="<?php echo esc_attr( $group['taxonomy'] ); ?>" value="<?php echo esc_attr( $term['slug'] ); ?>">
					<span data-attr-chip class="inline-block rounded-full border border-slate-200 bg-slate-50 px-3.5 py-1.5 text-[12px] font-bold text-slate-600 transition-all hover:border-emerald-400 hover:text-emerald-700"><?php echo esc_html( $term['name'] ); ?></span>
				</label>
				<?php endforeach; ?>
			</div>
		</div>
		<?php endforeach; ?>
	</div>
	<?php endif; ?>

	<div class="mb-6 flex flex-wrap items-center justify-center gap-2.5" data-shop-toolbar-pills<?php erfanpro_reveal( 0.07, 'inview', null, 'opacity: 1; filter: blur(0px); transform: none;' ); ?>>
		<button type="button" data-shop-cat="" class="<?php echo esc_attr( '' === $erfanpro_shop_params['cat'] ? $erfanpro_tb_pill_active : $erfanpro_tb_pill_inactive ); ?>"><?php esc_html_e( 'همه', 'erfanpro' ); ?></button>
		<?php foreach ( $erfanpro_tb_cats as $term ) : ?>
		<button type="button" data-shop-cat="<?php echo esc_attr( $term->slug ); ?>" class="<?php echo esc_attr( $term->slug === $erfanpro_shop_params['cat'] ? $erfanpro_tb_pill_active : $erfanpro_tb_pill_inactive ); ?>"><?php echo esc_html( $term->name ); ?></button>
		<?php endforeach; ?>
		<button type="button" data-shop-purchasable class="<?php echo esc_attr( $erfanpro_purch_inactive ); ?>">
			<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shopping-cart h-4 w-4" aria-hidden="true"><path d="m2.05 2.05 1.099-.028a1 1 0 0 1 1.008.815l2.69 14.347A1 1 0 0 0 7.83 18H18"></path><path d="M4.563 5h16.435a1 1 0 0 1 .981 1.204l-1.026 6.226A2 2 0 0 1 18.962 14H6.25"></path><circle cx="18" cy="20" r="2"></circle><circle cx="8" cy="20" r="2"></circle></svg>
			<?php esc_html_e( 'فقط قابل خرید', 'erfanpro' ); ?>
		</button>
	</div>

	<p id="erfanpro-shop-count" class="mb-5 text-center text-[13px] font-bold text-slate-500"><?php echo esc_html( erfanpro_shop_count_text( $erfanpro_shown, $erfanpro_shop_result['total'] ) ); ?></p>

	<div id="erfanpro-shop-grid" class="grid gap-7 sm:grid-cols-2 lg:grid-cols-3" aria-live="polite">
		<?php echo $erfanpro_shop_result['html']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	</div>

	<div id="erfanpro-shop-pagination">
		<?php echo erfanpro_shop_pagination_html( $erfanpro_shop_result['max_pages'], $erfanpro_shop_params['paged'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	</div>
</section>
