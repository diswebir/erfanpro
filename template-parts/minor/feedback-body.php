<?php
/**
 * بازخورد مشتریان body section — extracted verbatim from page-feedback.php
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

$erfanpro_counters = $args['counters'];
$erfanpro_listen_title = $args['listen_title'];
$erfanpro_listen_desc = $args['listen_desc'];
$erfanpro_cta1 = $args['cta1'];
$erfanpro_cta2 = $args['cta2'];
?>
	<section class="mx-auto max-w-7xl px-5 py-16 lg:px-8 lg:py-20">
		<div class="grid gap-6 text-center sm:grid-cols-3">
			<div class="rounded-[2rem] border border-slate-100 bg-white p-8 shadow-sm">
				<span class="text-[2rem] font-black text-emerald-700"><?php echo esc_html( $erfanpro_counters[0]['value'] ); ?></span>
				<p class="mt-2 text-[13px] font-bold text-slate-500"><?php echo esc_html( $erfanpro_counters[0]['label'] ); ?></p>
			</div>
			<div class="rounded-[2rem] border border-slate-100 bg-white p-8 shadow-sm">
				<span class="text-[2rem] font-black text-emerald-700"><?php echo esc_html( $erfanpro_counters[1]['value'] ); ?></span>
				<p class="mt-2 text-[13px] font-bold text-slate-500"><?php echo esc_html( $erfanpro_counters[1]['label'] ); ?></p>
			</div>
			<div class="rounded-[2rem] border border-slate-100 bg-white p-8 shadow-sm">
				<span class="text-[2rem] font-black text-emerald-700"><?php echo esc_html( $erfanpro_counters[2]['value'] ); ?></span>
				<p class="mt-2 text-[13px] font-bold text-slate-500"><?php echo esc_html( $erfanpro_counters[2]['label'] ); ?></p>
			</div>
		</div>
		<div class="mt-6 rounded-[2rem] border border-slate-100 bg-white p-8 shadow-sm lg:p-10">
			<h2 class="text-lg font-extrabold text-slate-900"><?php echo esc_html( $erfanpro_listen_title ); ?></h2>
			<p class="mt-3 text-[15px] leading-9 text-slate-600"><?php echo esc_html( $erfanpro_listen_desc ); ?></p>
			<p class="mt-6 flex flex-wrap gap-3">
				<a class="inline-flex items-center rounded-full bg-emerald-700 px-6 py-3 text-sm font-extrabold text-white transition-colors hover:bg-emerald-800" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php echo esc_html( $erfanpro_cta1 ); ?></a>
				<a class="inline-flex items-center rounded-full border border-emerald-700 px-6 py-3 text-sm font-extrabold text-emerald-800 transition-colors hover:bg-emerald-50" href="<?php echo esc_url( get_post_type_archive_link( 'projects' ) ); ?>"><?php echo esc_html( $erfanpro_cta2 ); ?></a>
			</p>
		</div>
	</section>
	