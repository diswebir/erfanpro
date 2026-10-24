<?php
/**
 * Footer — phase 2 replaces this skeleton with the reference footer.
 * The floating button is back-to-top only, on desktop and mobile
 * (project decision 26).
 *
 * @package Erfanpro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<button type="button" id="erfanpro-to-top" class="erfanpro-to-top" aria-label="<?php echo esc_attr__( 'بازگشت به بالا', 'erfanpro' ); ?>">
	<span aria-hidden="true">&#8593;</span>
</button>
<?php wp_footer(); ?>
</body>
</html>
