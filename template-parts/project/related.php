<?php
/**
 * Phase 6: «پروژه‌های مرتبط دیگر» — same city, then same
 * project_type, then latest (decision 17, automatic), rendered
 * with the phase-6 project card.
 *
 * @package Erfanpro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$erfanpro_st = erfanpro_section_texts( 'project_related' );

$erfanpro_project_id      = get_queried_object_id();
$erfanpro_related_projects = erfanpro_related_projects_for_project( $erfanpro_project_id, 3 );

if ( empty( $erfanpro_related_projects ) ) {
	return;
}
?>
<section class="mx-auto max-w-7xl px-5 pb-16 lg:px-8 lg:pb-20">
	<div class="mt-4 border-t border-slate-200/80 pt-16">
		<div class="mb-8 flex items-center justify-between"<?php erfanpro_reveal( 0.00, 'inview', null, 'opacity: 1; filter: blur(0px); transform: none;' ); ?>>
			<h3 class="text-2xl font-extrabold text-slate-900"><?php echo esc_html( erfanpro_panel_text( $erfanpro_st, 'title', __( 'پروژه‌های مرتبط دیگر', 'erfanpro' ) ) ); ?></h3>
			<a class="group flex items-center gap-2 text-sm font-bold text-emerald-700 hover:text-emerald-800" href="<?php echo esc_url( erfanpro_archive_url( 'projects' ) ); ?>"><?php echo esc_html( erfanpro_panel_text( $erfanpro_st, 'cta', __( 'مشاهده آرشیو کامل پروژه‌ها', 'erfanpro' ) ) ); ?><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-up-left h-4 w-4 transition-transform group-hover:-translate-x-0.5 group-hover:-translate-y-0.5" aria-hidden="true"><path d="M7 17V7h10"></path><path d="M17 17 7 7"></path></svg></a>
		</div>
		<div class="grid gap-7 sm:grid-cols-2 lg:grid-cols-3">
			<?php foreach ( $erfanpro_related_projects as $i => $related_id ) : ?>
				<?php echo erfanpro_project_card_html( $related_id, $i * 0.07 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<?php endforeach; ?>
		</div>
	</div>
</section>
