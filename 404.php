<?php
/**
 * 404 — phase 9 rebuilds this pixel-faithful to the static reference
 * (project decision 30). Skeleton for now.
 *
 * @package Erfanpro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>
<main id="main" class="site-main">
	<h1><?php esc_html_e( 'صفحه پیدا نشد', 'erfanpro' ); ?></h1>
	<p><?php esc_html_e( 'صفحه‌ای که دنبالش بودید وجود ندارد یا منتقل شده است.', 'erfanpro' ); ?></p>
	<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'بازگشت به خانه', 'erfanpro' ); ?></a>
</main>
<?php
get_footer();
