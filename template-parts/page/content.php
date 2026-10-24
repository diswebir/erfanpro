<?php
/**
 * Shared page-content section (phase 9).
 *
 * Used by the about / contact page templates: when the page has editor
 * content of its own, it renders in a reference-styled card below the
 * reference sections (plan §4: about content sections come from the
 * page content + options; the reference sections above are the
 * pixel-faithful default when the editor is empty).
 *
 * @package Erfanpro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! have_posts() ) {
	return;
}

while ( have_posts() ) {
	the_post();
	$erfanpro_page_content = get_the_content();
	if ( '' === trim( (string) $erfanpro_page_content ) ) {
		continue;
	}
	?>
	<section class="mx-auto max-w-7xl px-5 py-16 lg:px-8 lg:py-20">
		<div class="card-light rounded-[2rem] p-8 lg:p-10">
			<div class="text-[15px] leading-9 text-slate-600 lg:leading-10">
				<?php the_content(); ?>
			</div>
		</div>
	</section>
	<?php
}
?>
