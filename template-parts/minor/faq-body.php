<?php
/**
 * سوالات متداول body section — extracted verbatim from page-faq.php
 * for the section manager; variables arrive via $args.
 *
 * NOTE: the file intentionally ends with a tab — it reproduces the
 * original assembler's inter-section whitespace (byte-identical output).
 *
 * @package Erfanpro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$erfanpro_faq_items = $args['items'];
$erfanpro_cta = $args['cta'];
?>
	<section class="mx-auto max-w-7xl px-5 py-16 lg:px-8 lg:py-20">
		<div class="mx-auto max-w-3xl space-y-4">
			<?php foreach ( $erfanpro_faq_items as $erfanpro_faq_index => $erfanpro_faq_item ) : ?>
				<div class="overflow-hidden rounded-[1.5rem] border border-slate-100 bg-white shadow-sm">
					<button type="button" class="erfanpro-faq-question flex w-full items-center justify-between gap-4 px-6 py-5 text-right text-[15px] font-extrabold text-slate-900" aria-expanded="false" aria-controls="erfanpro-faq-panel-<?php echo esc_attr( $erfanpro_faq_index ); ?>">
						<span><?php echo esc_html( $erfanpro_faq_item['q'] ); ?></span>
						<span aria-hidden="true" class="shrink-0 text-emerald-700">＋</span>
					</button>
					<div id="erfanpro-faq-panel-<?php echo esc_attr( $erfanpro_faq_index ); ?>" hidden class="px-6 pb-6 text-[15px] leading-9 text-slate-600"><?php echo esc_html( $erfanpro_faq_item['a'] ); ?></div>
				</div>
			<?php endforeach; ?>
		</div>
		<p class="mt-10 text-center">
			<a class="inline-flex items-center rounded-full bg-emerald-700 px-6 py-3 text-sm font-extrabold text-white transition-colors hover:bg-emerald-800" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php echo esc_html( $erfanpro_cta ); ?></a>
		</p>
	</section>
	