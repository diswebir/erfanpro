<?php
/**
 * Floating buttons — decision 26: back-to-top ONLY, on desktop and
 * mobile alike. No chat/call floating buttons exist in this theme.
 * The button appears after scrolling and smooth-scrolls to the top;
 * the same script also wires the footer's bottom-bar back-to-top
 * button ([data-to-top]).
 *
 * @package Erfanpro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$erfanpro_ft = erfanpro_section_texts( 'footer_texts' );
?>
<button type="button" id="erfanpro-to-top" class="erfanpro-to-top" data-to-top aria-label="<?php echo esc_attr( erfanpro_panel_text( $erfanpro_ft, 'back_to_top', __( 'بازگشت به بالا', 'erfanpro' ) ) ); ?>" style="position:fixed;left:1.25rem;bottom:1.25rem;z-index:60;width:2.75rem;height:2.75rem;border-radius:9999px;background:#059669;color:#fff;display:grid;place-items:center;box-shadow:0 10px 25px -10px rgba(5,150,105,.55);opacity:0;pointer-events:none;transform:translateY(10px);transition:opacity .35s ease,transform .35s ease;">
	<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-up h-5 w-5" aria-hidden="true"><path d="m5 12 7-7 7 7"></path><path d="M12 19V5"></path></svg>
</button>
<script>
(function () {
	var btn = document.getElementById('erfanpro-to-top');
	function onScroll() {
		if (!btn) return;
		var show = window.scrollY > 600;
		btn.style.opacity = show ? '1' : '0';
		btn.style.pointerEvents = show ? 'auto' : 'none';
		btn.style.transform = show ? 'translateY(0)' : 'translateY(10px)';
	}
	window.addEventListener('scroll', onScroll, { passive: true });
	onScroll();
	document.querySelectorAll('[data-to-top]').forEach(function (el) {
		el.addEventListener('click', function () {
			window.scrollTo({ top: 0, behavior: 'smooth' });
		});
	});
})();
</script>
