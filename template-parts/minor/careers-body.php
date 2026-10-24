<?php
/**
 * فرصت‌های شغلی body section — extracted verbatim from page-careers.php
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

$erfanpro_areas = $args['areas'];
$erfanpro_resume_title = $args['resume_title'];
$erfanpro_resume_desc = $args['resume_desc'];
$erfanpro_cta = $args['cta'];
?>
	<section class="mx-auto max-w-7xl px-5 py-16 lg:px-8 lg:py-20">
		<div class="grid gap-6 md:grid-cols-3">
			<div class="rounded-[2rem] border border-slate-100 bg-white p-8 shadow-sm">
				<h2 class="text-lg font-extrabold text-slate-900"><?php echo esc_html( $erfanpro_areas[0]['title'] ); ?></h2>
				<p class="mt-3 text-[15px] leading-9 text-slate-600"><?php echo esc_html( $erfanpro_areas[0]['desc'] ); ?></p>
			</div>
			<div class="rounded-[2rem] border border-slate-100 bg-white p-8 shadow-sm">
				<h2 class="text-lg font-extrabold text-slate-900"><?php echo esc_html( $erfanpro_areas[1]['title'] ); ?></h2>
				<p class="mt-3 text-[15px] leading-9 text-slate-600"><?php echo esc_html( $erfanpro_areas[1]['desc'] ); ?></p>
			</div>
			<div class="rounded-[2rem] border border-slate-100 bg-white p-8 shadow-sm">
				<h2 class="text-lg font-extrabold text-slate-900"><?php echo esc_html( $erfanpro_areas[2]['title'] ); ?></h2>
				<p class="mt-3 text-[15px] leading-9 text-slate-600"><?php echo esc_html( $erfanpro_areas[2]['desc'] ); ?></p>
			</div>
		</div>
		<div class="mt-6 rounded-[2rem] border border-slate-100 bg-white p-8 shadow-sm lg:p-10">
			<h2 class="text-lg font-extrabold text-slate-900"><?php echo esc_html( $erfanpro_resume_title ); ?></h2>
			<p class="mt-3 text-[15px] leading-9 text-slate-600"><?php echo esc_html( $erfanpro_resume_desc ); ?></p>
			<p class="mt-6">
				<a class="inline-flex items-center rounded-full bg-emerald-700 px-6 py-3 text-sm font-extrabold text-white transition-colors hover:bg-emerald-800" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php echo esc_html( $erfanpro_cta ); ?></a>
			</p>
		</div>
	</section>
	