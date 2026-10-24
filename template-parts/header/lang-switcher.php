<?php
/**
 * Language switcher (decision 9) — fa is the current site; en/ar point
 * at the hand-entered counterpart URLs from the post metabox
 * (inc/multilang.php: erfanpro_link_en / erfanpro_link_ar). Rendered
 * only when at least one counterpart exists for the current object.
 *
 * @package Erfanpro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$erfanpro_lang_en = erfanpro_lang_url( 'en' );
$erfanpro_lang_ar = erfanpro_lang_url( 'ar' );

if ( ! $erfanpro_lang_en && ! $erfanpro_lang_ar ) {
	return;
}
?>
<div class="hidden items-center gap-2 rounded-full border border-slate-200 bg-white px-2 py-1 text-[11.5px] font-bold text-slate-500 sm:flex" role="group" aria-label="<?php esc_attr_e( 'زبان', 'erfanpro' ); ?>">
	<span class="text-emerald-700" aria-current="true">FA</span>
	<?php if ( $erfanpro_lang_en ) : ?>
		<a href="<?php echo esc_url( $erfanpro_lang_en ); ?>" hreflang="en" class="transition-colors hover:text-emerald-700">EN</a>
	<?php endif; ?>
	<?php if ( $erfanpro_lang_ar ) : ?>
		<a href="<?php echo esc_url( $erfanpro_lang_ar ); ?>" hreflang="ar" class="transition-colors hover:text-emerald-700"><?php esc_html_e( 'عربي', 'erfanpro' ); ?></a>
	<?php endif; ?>
</div>
