<?php
/**
 * Search results — phase 9 rebuilds this pixel-faithful to the static
 * reference (project decision 30); the live AJAX search itself is
 * decision 20. Skeleton for now.
 *
 * @package Erfanpro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>
<main id="main" class="site-main">
	<h1>
		<?php
		printf(
			/* translators: %s: search query. */
			esc_html__( 'نتایج جستجو برای: %s', 'erfanpro' ),
			esc_html( get_search_query() )
		);
		?>
	</h1>
	<?php
	if ( have_posts() ) {
		while ( have_posts() ) {
			the_post();
			?>
			<article <?php post_class(); ?>>
				<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
				<div class="entry-summary"><?php the_excerpt(); ?></div>
			</article>
			<?php
		}
		the_posts_pagination();
	} else {
		?>
		<p><?php esc_html_e( 'برای جستجوی شما نتیجه‌ای پیدا نشد.', 'erfanpro' ); ?></p>
		<?php
	}
	?>
</main>
<?php
get_footer();
