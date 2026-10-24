<?php
/**
 * Phase 7: services archive list — one alternating row per service
 * (image + number badge, kicker, title, description, feature bullets,
 * «جزئیات این خدمت» / «درخواست مشاوره» buttons).
 *
 * Pixel clone of the static reference #services-list. Rows alternate
 * sides via lg:[direction:ltr] exactly like the reference.
 *
 * @package Erfanpro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$erfanpro_services = erfanpro_services_all();

if ( empty( $erfanpro_services ) ) {
	return;
}
?>
<section id="services-list" class="mx-auto max-w-7xl scroll-mt-24 px-5 py-16 lg:px-8 lg:py-20">
	<div class="flex flex-col gap-16 lg:gap-24">
<?php
foreach ( $erfanpro_services as $erfanpro_idx => $erfanpro_sid ) :
	$erfanpro_num     = $erfanpro_idx + 1;
	$erfanpro_flip    = 1 === $erfanpro_idx % 2;
	$erfanpro_title   = get_the_title( $erfanpro_sid );
	$erfanpro_url     = get_permalink( $erfanpro_sid );
	$erfanpro_img     = erfanpro_service_image( $erfanpro_sid );
	$erfanpro_kicker  = erfanpro_service_kicker( $erfanpro_sid );
	$erfanpro_excerpt = get_the_excerpt( $erfanpro_sid );
	$erfanpro_desc    = '' !== $erfanpro_excerpt ? $erfanpro_excerpt : wp_trim_words( wp_strip_all_tags( get_post_field( 'post_content', $erfanpro_sid ) ), 45 );
	$erfanpro_bullets = array();
	foreach ( array_slice( erfanpro_service_items( $erfanpro_sid, 'service_features' ), 0, 4 ) as $erfanpro_f ) {
		$erfanpro_t = trim( (string) ( $erfanpro_f['title'] ?? '' ) );
		if ( '' !== $erfanpro_t ) {
			$erfanpro_bullets[] = $erfanpro_t;
		}
	}
	$erfanpro_row_delay = 0.07 * ( $erfanpro_idx % 3 );
?>
		<div<?php erfanpro_reveal( $erfanpro_row_delay, 'inview', null, 'opacity: 1; filter: blur(0px); transform: none;' ); ?>>
			<div class="grid items-center gap-8 lg:grid-cols-2 lg:gap-14<?php echo $erfanpro_flip ? ' lg:[direction:ltr]' : ''; ?>">
				<div class="[direction:rtl]">
					<div class="relative overflow-hidden rounded-[1.75rem] border border-emerald-100 shadow-lg">
						<img alt="<?php echo esc_attr( $erfanpro_title ); ?>" loading="lazy" class="h-72 w-full object-cover lg:h-80" src="<?php echo esc_url( $erfanpro_img ); ?>">
						<span class="absolute right-5 top-5 grid h-12 w-12 place-items-center rounded-2xl bg-emerald-950/80 text-xl font-black text-emerald-200 backdrop-blur-md"><?php echo esc_html( erfanpro_fa_digits( str_pad( (string) $erfanpro_num, 2, '0', STR_PAD_LEFT ) ) ); ?></span>
					</div>
				</div>
				<div class="flex flex-col items-start gap-5 [direction:rtl]">
					<?php if ( '' !== $erfanpro_kicker ) : ?>
					<span class="rounded-full border border-emerald-200 bg-emerald-50 px-4 py-1.5 text-[12.5px] font-bold text-emerald-800"><?php echo esc_html( $erfanpro_kicker ); ?></span>
					<?php endif; ?>
					<h2 class="text-2xl font-extrabold text-slate-900 lg:text-3xl"><?php echo esc_html( $erfanpro_title ); ?></h2>
					<p class="text-[15px] leading-9 text-slate-600"><?php echo esc_html( $erfanpro_desc ); ?></p>
					<?php if ( ! empty( $erfanpro_bullets ) ) : ?>
					<ul class="grid gap-3 sm:grid-cols-2">
						<?php foreach ( $erfanpro_bullets as $erfanpro_bullet ) : ?>
						<li class="flex items-start gap-2.5 text-[13.5px] font-semibold leading-7 text-slate-700"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-check lucide-check-circle-2 mt-1 h-4.5 w-4.5 shrink-0 text-emerald-600" aria-hidden="true"><circle cx="12" cy="12" r="10"></circle><path d="m16 9-5.5 5.5L8 12"></path></svg><?php echo esc_html( $erfanpro_bullet ); ?></li>
						<?php endforeach; ?>
					</ul>
					<?php endif; ?>
					<div class="mt-2 flex flex-wrap gap-3">
						<a class="group inline-flex items-center gap-2.5 rounded-full bg-emerald-700 px-7 py-3.5 text-[14px] font-extrabold text-white shadow-lg shadow-emerald-700/25 transition-all hover:bg-emerald-800" href="<?php echo esc_url( $erfanpro_url ); ?>"><?php esc_html_e( 'جزئیات این خدمت', 'erfanpro' ); ?><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-up-left h-4 w-4 transition-transform group-hover:-translate-x-0.5 group-hover:-translate-y-0.5" aria-hidden="true"><path d="M7 17V7h10"></path><path d="M17 17 7 7"></path></svg></a>
						<a class="inline-flex items-center gap-2.5 rounded-full border border-emerald-200 bg-white px-7 py-3.5 text-[14px] font-extrabold text-emerald-800 transition-all hover:border-emerald-400 hover:bg-emerald-50" href="<?php echo esc_url( erfanpro_contact_url() ); ?>"><?php esc_html_e( 'درخواست مشاوره', 'erfanpro' ); ?></a>
					</div>
				</div>
			</div>
		</div>
<?php endforeach; ?>
	</div>
</section>
