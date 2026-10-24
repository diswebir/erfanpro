<?php
/**
 * Home — cities marquee (phase 3).
 *
 * Decision 23: city names come from the `city` taxonomy terms
 * (blueprint: 20 terms). The list is rendered twice for the seamless
 * CSS marquee loop; the reference city list is the design fallback.
 *
 * @package Erfanpro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$erfanpro_cities = array();
if ( taxonomy_exists( 'city' ) ) {
	$terms = get_terms(
		array(
			'taxonomy'   => 'city',
			'hide_empty' => false,
			'orderby'    => 'name',
			'order'      => 'ASC',
		)
	);
	if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
		foreach ( $terms as $term ) {
			$erfanpro_cities[] = $term->name;
		}
	}
}
if ( empty( $erfanpro_cities ) ) {
	$erfanpro_cities = array(
		'اصفهان',
		'تهران',
		'شیراز',
		'مشهد',
		'کرمان',
		'البرز',
		'تبریز',
		'اهواز',
		'قم',
		'رشت',
		'یزد',
		'اردبیل',
		'خراسان',
		'گیلان',
	);
}
?>
<section class="relative z-10 border-y border-emerald-100 bg-white/85 py-8 shadow-sm backdrop-blur-sm">
	<p class="mb-6 text-center text-[13px] font-semibold tracking-wide text-slate-600"><?php esc_html_e( 'جلب اعتماد شما باعث افتخار ماست — مفتخریم از همکاری با تمام شهرهای کشور', 'erfanpro' ); ?></p>
	<div class="mask-fade-x flex overflow-hidden">
		<div class="flex shrink-0 items-center gap-0 py-1 animate-marquee">
			<?php for ( $erfanpro_dup = 0; $erfanpro_dup < 2; $erfanpro_dup++ ) : ?>
				<?php foreach ( $erfanpro_cities as $erfanpro_city ) : ?>
			<span class="mx-6 flex items-center gap-6 whitespace-nowrap text-[15px] font-bold text-slate-700 lg:text-base"><?php echo esc_html( $erfanpro_city ); ?><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-lightbulb h-4 w-4 text-emerald-600" aria-hidden="true"><path d="M15 14c.2-1 .7-1.7 1.5-2.5 1-.9 1.5-2.2 1.5-3.5A6 6 0 0 0 6 8c0 1 .2 2.2 1.5 3.5.7.7 1.3 1.5 1.5 2.5"></path><path d="M9 18h6"></path><path d="M10 22h4"></path></svg></span>
				<?php endforeach; ?>
			<?php endfor; ?>
		</div>
	</div>
</section>
