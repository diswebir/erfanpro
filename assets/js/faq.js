/**
 * Phase 10: site FAQ accordion (page-faq.php).
 *
 * Plain vanilla JS, no dependencies, no external requests. Buttons
 * carry aria-expanded and toggle their paired panel; only one panel
 * stays open at a time, matching the product-tab FAQ behaviour.
 */
(function () {
	'use strict';

	document.addEventListener( 'DOMContentLoaded', function () {
		var buttons = document.querySelectorAll( '.erfanpro-faq-question' );
		if ( ! buttons.length ) {
			return;
		}
		buttons.forEach( function ( button ) {
			button.addEventListener( 'click', function () {
				var panel = document.getElementById( button.getAttribute( 'aria-controls' ) );
				var isOpen = 'true' === button.getAttribute( 'aria-expanded' );
				buttons.forEach( function ( other ) {
					other.setAttribute( 'aria-expanded', 'false' );
					var otherPanel = document.getElementById( other.getAttribute( 'aria-controls' ) );
					if ( otherPanel ) {
						otherPanel.hidden = true;
					}
				} );
				if ( ! isOpen && panel ) {
					button.setAttribute( 'aria-expanded', 'true' );
					panel.hidden = false;
				}
			} );
		} );
	} );
})();
