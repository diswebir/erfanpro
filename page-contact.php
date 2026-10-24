<?php
/**
 * Template Name: تماس با ما
 *
 * Phase 9: contact page assembler — pixel clone of the static reference
 * contact.html (hero, info cards, live consultation form + aside).
 * The form posts to the phase-1 leads infrastructure
 * (inc/contact-form.php -> erfanpro_save_lead(), form_id contact-main).
 *
 * @package Erfanpro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>
<main class="min-h-screen bg-[#f8faf9]">
	<?php
	get_template_part( 'template-parts/contact/hero' );
	get_template_part( 'template-parts/contact/body' );
	get_template_part( 'template-parts/page/content' );
	?>
</main>
<?php
get_footer();
