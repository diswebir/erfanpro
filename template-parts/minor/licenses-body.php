<?php
/**
 * مجوزها و افتخارات body section — extracted verbatim from page-licenses.php
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

$erfanpro_cards = $args['cards'];
$erfanpro_bottom_title = $args['bottom_title'];
$erfanpro_bottom_desc = $args['bottom_desc'];
$erfanpro_cta = $args['cta'];
?>
	<section class="mx-auto max-w-7xl px-5 py-16 lg:px-8 lg:py-20">
		<div class="grid gap-6 md:grid-cols-2">
			<div class="rounded-[2rem] border border-slate-100 bg-white p-8 shadow-sm">
				<span class="inline-flex rounded-full bg-amber-100 px-4 py-1 text-[12px] font-extrabold text-amber-800"><?php echo esc_html( $erfanpro_cards[0]['badge'] ); ?></span>
				<h2 class="mt-4 text-lg font-extrabold text-slate-900"><?php echo esc_html( $erfanpro_cards[0]['title'] ); ?></h2>
				<p class="mt-3 text-[15px] leading-9 text-slate-600"><?php echo esc_html( $erfanpro_cards[0]['desc'] ); ?></p>
			</div>
			<div class="rounded-[2rem] border border-slate-100 bg-white p-8 shadow-sm">
				<span class="inline-flex rounded-full bg-emerald-100 px-4 py-1 text-[12px] font-extrabold text-emerald-800"><?php echo esc_html( $erfanpro_cards[1]['badge'] ); ?></span>
				<h2 class="mt-4 text-lg font-extrabold text-slate-900"><?php echo esc_html( $erfanpro_cards[1]['title'] ); ?></h2>
				<p class="mt-3 text-[15px] leading-9 text-slate-600"><?php echo esc_html( $erfanpro_cards[1]['desc'] ); ?></p>
			</div>
		</div>
		<div class="mt-6 rounded-[2rem] border border-slate-100 bg-white p-8 shadow-sm lg:p-10">
			<h2 class="text-lg font-extrabold text-slate-900"><?php echo esc_html( $erfanpro_bottom_title ); ?></h2>
			<p class="mt-3 text-[15px] leading-9 text-slate-600"><?php echo esc_html( $erfanpro_bottom_desc ); ?></p>
			<p class="mt-6">
				<a class="inline-flex items-center rounded-full bg-emerald-700 px-6 py-3 text-sm font-extrabold text-white transition-colors hover:bg-emerald-800" href="<?php echo esc_url( get_post_type_archive_link( 'projects' ) ); ?>"><?php echo esc_html( $erfanpro_cta ); ?></a>
			</p>
		</div>
	</section>
	