<?php
/**
 * Helpers: theme options reader + animation attribute emitters.
 *
 * The animation attributes replicate, one-to-one, the baked attributes of
 * the static reference (main-erfansanat-static): data-anim, data-anim-init,
 * data-anim-final, data-anim-kind, data-anim-delay. Values recorded from
 * the original framer-motion build: Reveal .9s cubic-bezier(.22,1,.36,1),
 * initial {opacity 0, translateY(42px), blur(6px)}, standard final visible
 * state {opacity 1, transform none}.
 *
 * @package Erfanpro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Read one theme option from the single `erfanpro_options` key.
 *
 * @param string $key     Option key.
 * @param mixed  $default Fallback value.
 * @return mixed
 */
function erfanpro_option( $key, $default = '' ) {
	$options = get_option( 'erfanpro_options', array() );
	if ( is_array( $options ) && isset( $options[ $key ] ) && '' !== $options[ $key ] ) {
		return $options[ $key ];
	}
	return $default;
}

/**
 * Echo the baked animation attributes for an element.
 *
 * @param float       $delay Delay in seconds (stagger).
 * @param string      $kind  Animation kind: reveal|mount.
 * @param string|null $init  Initial inline style; null = standard reveal initial.
 * @param string|null $final Final inline style; null = standard visible final.
 */
function erfanpro_reveal( $delay = 0.0, $kind = 'reveal', $init = null, $final = null ) {
	if ( null === $init ) {
		$init = 'mount' === $kind
			? 'opacity: 0; transform: translateY(18px);'
			: 'opacity: 0; transform: translateY(42px); filter: blur(6px);';
	}
	if ( null === $final ) {
		$final = 'opacity: 1; transform: none;';
	}
	printf(
		' data-anim="" data-anim-init="%1$s" data-anim-final="%2$s" data-anim-kind="%3$s" data-anim-delay="%4$s" style="%2$s"',
		esc_attr( $init ),
		esc_attr( $final ),
		esc_attr( $kind ),
		esc_attr( number_format( (float) $delay, 2, '.', '' ) )
	);
}

/**
 * Echo the counter attributes used by the About/home counters
 * (0 -> value in 2.2s, Persian digits; runtime lives in anim.js).
 *
 * @param int   $to    Target number.
 * @param float $delay Delay in seconds.
 */
function erfanpro_counter( $to, $delay = 0.0 ) {
	printf(
		' data-counter-to="%1$s" data-counter-final="%1$s" data-counter-delay="%2$s"',
		esc_attr( (string) (int) $to ),
		esc_attr( number_format( (float) $delay, 2, '.', '' ) )
	);
}
