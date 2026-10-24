<?php
/**
 * Header search bar — a standard WordPress search form (GET ?s=) in a
 * collapsible row under the header bar, toggled by the search button
 * in the header actions. Appearance follows the reference search
 * inputs (rounded-2xl, emerald accents). Phase 11 adds the AJAX live
 * search of decision 20 on top: typing fills the dropdown below the
 * input (assets/js/header-search.js + inc/live-search.php); the GET
 * form remains the fallback when JS or the request fails.
 *
 * @package Erfanpro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div id="erfanpro-search-bar" class="hidden border-t border-emerald-100 bg-white/95" hidden>
	<div class="mx-auto max-w-7xl px-4 py-3 lg:px-8">
		<form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" class="flex items-center gap-2">
			<div class="relative flex-1">
				<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search absolute right-4 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400" aria-hidden="true"><path d="m21 21-4.34-4.34"></path><circle cx="11" cy="11" r="8"></circle></svg>
				<input type="search" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="<?php esc_attr_e( 'جستجو در محصولات، پروژه‌ها و مقالات…', 'erfanpro' ); ?>" class="w-full rounded-2xl border border-emerald-100 bg-slate-50 py-3 pl-5 pr-13 text-[13.5px] font-medium text-slate-800 outline-none transition-all placeholder:text-slate-400" autocomplete="off" />
				<div id="erfanpro-search-results" class="absolute inset-x-0 top-full z-50 mt-2 hidden overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-xl"></div>
			</div>
			<button type="submit" class="rounded-2xl bg-emerald-600 px-5 py-3 text-[13.5px] font-bold text-white transition-colors hover:bg-emerald-700"><?php esc_html_e( 'جستجو', 'erfanpro' ); ?></button>
		</form>
	</div>
</div>
<script>
(function () {
	var toggle = document.querySelector('[data-erfanpro-search-toggle]');
	var bar = document.getElementById('erfanpro-search-bar');
	if (!toggle || !bar) return;
	function setOpen(open) {
		bar.hidden = !open;
		bar.classList.toggle('hidden', !open);
		toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
		if (open) {
			var input = bar.querySelector('input[type="search"]');
			if (input) input.focus();
		}
	}
	toggle.addEventListener('click', function () {
		setOpen(bar.hidden);
	});
	document.addEventListener('keydown', function (e) {
		if (e.key === 'Escape' && !bar.hidden) setOpen(false);
	});
})();
</script>
