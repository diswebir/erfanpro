<?php
/**
 * Phase 7: single service FAQ — accordion over the service_faq
 * repeater (plan section 4.7). Same accordion mechanics as the product
 * page FAQ (data-faq-* hooks, anim.js); rendered only when the service
 * has FAQ rows.
 *
 * @package Erfanpro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$erfanpro_st = erfanpro_section_texts( 'service_faq' );

$erfanpro_id  = get_queried_object_id();
$erfanpro_faq = array();
foreach ( erfanpro_service_items( $erfanpro_id, 'service_faq' ) as $erfanpro_row ) {
	$erfanpro_q = trim( (string) ( $erfanpro_row['question'] ?? '' ) );
	if ( '' === $erfanpro_q ) {
		continue;
	}
	$erfanpro_faq[] = array(
		'q' => $erfanpro_q,
		'a' => trim( (string) ( $erfanpro_row['answer'] ?? '' ) ),
	);
}

if ( empty( $erfanpro_faq ) ) {
	return;
}

$erfanpro_icon_on  = 'flex h-7 w-7 shrink-0 items-center justify-center rounded-lg text-lg font-black transition-transform duration-200 bg-emerald-800 text-white rotate-45';
$erfanpro_icon_off = 'flex h-7 w-7 shrink-0 items-center justify-center rounded-lg text-lg font-black transition-transform duration-200 bg-emerald-800 text-white';
?>
<section class="mx-auto max-w-7xl scroll-mt-24 px-5 pb-14 lg:px-8 lg:pb-18">
	<div class="rounded-3xl border border-emerald-100 bg-white p-7 shadow-sm lg:p-9">
		<div<?php erfanpro_reveal( 0.00, 'inview', null, 'opacity: 1; filter: blur(0px); transform: none;' ); ?>>
			<h2 class="mb-6 flex items-center gap-3 text-xl font-extrabold text-slate-900 lg:text-2xl"><span class="h-7 w-1.5 rounded-full bg-gradient-to-b from-emerald-500 to-emerald-700"></span><?php echo esc_html( erfanpro_panel_text( $erfanpro_st, 'title', __( 'پرسش‌های متداول', 'erfanpro' ) ) ); ?></h2>
		</div>
		<div class="space-y-3">
			<?php foreach ( $erfanpro_faq as $erfanpro_i => $erfanpro_item ) :
				$erfanpro_open = 0 === $erfanpro_i;
			?>
			<div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-slate-50/50 transition-colors hover:border-emerald-200" data-faq-item="<?php echo esc_attr( (string) $erfanpro_i ); ?>">
				<button type="button" class="flex w-full items-center justify-between gap-4 px-6 py-4.5 text-right font-bold text-slate-800 transition-colors" data-faq-btn="">
					<span class="text-[14.5px]"><?php echo esc_html( $erfanpro_item['q'] ); ?></span>
					<span class="<?php echo esc_attr( $erfanpro_open ? $erfanpro_icon_on : $erfanpro_icon_off ); ?>" data-icon-on="<?php echo esc_attr( $erfanpro_icon_on ); ?>" data-icon-off="<?php echo esc_attr( $erfanpro_icon_off ); ?>">+</span>
				</button>
				<div class="overflow-hidden"<?php echo $erfanpro_open ? ' style="height: auto; opacity: 1;"' : ' style="display: none;"'; ?> data-faq-answer="">
					<p class="border-t border-slate-200/80 bg-white px-6 py-4.5 text-[14px] leading-8 text-slate-600"><?php echo wp_kses_post( $erfanpro_item['a'] ); ?></p>
				</div>
			</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
