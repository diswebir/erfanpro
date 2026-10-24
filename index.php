<?php
/**
 * Fallback template — the loop. Specific templates land per phase.
 *
 * @package Erfanpro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>
<main id="main" class="site-main">
	<?php
	if ( have_posts() ) {
		while ( have_posts() ) {
			the_post();
			?>
			<article <?php post_class(); ?>>
				<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
				<div class="entry-content"><?php the_content(); ?></div>
			</article>
			<?php
		}
		the_posts_pagination();
	} else {
		?>
		<p><?php esc_html_e( 'موردی پیدا نشد.', 'erfanpro' ); ?></p>
		<?php
	}
	?>
</main>
<?php
get_footer();
