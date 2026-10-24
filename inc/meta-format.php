<?php
/**
 * Phase 1 — data layer: JetEngine-compatible meta read/write helpers.
 *
 * The blueprint's live erfansanat.com database stores JetEngine meta-box
 * values in these exact shapes (verified 2026-10-21 against the real
 * database values in the goal's hidden_files/meta-samples.md — no guessing):
 *
 * - repeaters (`faq`, `mfaq`, `usage_product`, `product-feature`,
 *   `product-feature2_copy`): PHP array keyed `item-0`, `item-1`, …
 *   (WordPress serializes it in postmeta; JetEngine reads the same)
 * - galleries (`galerry_produ`, `gallery-filed`, `gallery-projects`):
 *   plain comma-separated attachment IDs, e.g. "12709,6297,6276"
 * - multi selects (`project-ids`, `usage_countr`, `mortabet_mahsole_2`,
 *   `product-in-projects`): arrays of ID strings
 * - single media (`image-2`…`image-6`, `product-catalog-file`,
 *   `second-image`…`six-image`): single attachment ID
 * - dates (`finish-pro-time`): Jalali string, e.g. "1404/01/01"
 *
 * Meta key names are NEVER renamed — including the historical typos
 * (`galerry_produ`, `three-image`, `foure-image`, `vaule_of_variable`,
 *   `english_titl`, `gallery-filed`) — so existing data keeps working.
 * The readers below are defensive: they accept serialized PHP arrays,
 * JSON strings and plain comma lists alike.
 *
 * Untouched by this layer (read-only for templates, never written here):
 * `hrefmeta_*`, all `rank_math_*` / `_seopress_*` SEO keys, WooCommerce
 * core keys (`_price`, `_product_attributes`, …) and `_kksr_*` ratings.
 *
 * @package Erfanpro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Read a JetEngine-style repeater field.
 *
 * Accepts the serialized PHP array JetEngine writes, a JSON object string
 * with `item-N` keys, or (defensively) a JSON list. Returns the items as a
 * plain zero-indexed list of assoc arrays, ordered by item number.
 *
 * @param int    $post_id Post ID.
 * @param string $key     Meta key.
 * @return array<int, array<string, mixed>>
 */
function erfanpro_get_repeater( $post_id, $key ) {
	$raw = get_post_meta( $post_id, $key, true );

	if ( is_string( $raw ) ) {
		$maybe = maybe_unserialize( $raw );
		if ( is_array( $maybe ) ) {
			$raw = $maybe;
		} else {
			$decoded = json_decode( $raw, true );
			if ( is_array( $decoded ) ) {
				$raw = $decoded;
			}
		}
	}

	if ( ! is_array( $raw ) || empty( $raw ) ) {
		return array();
	}

	// Normalize `item-N` keys to a sorted list.
	$ordered = array();
	foreach ( $raw as $k => $item ) {
		if ( preg_match( '/^item-(\d+)$/', (string) $k, $m ) ) {
			$ordered[ (int) $m[1] ] = is_array( $item ) ? $item : array();
		} elseif ( is_array( $item ) ) {
			$ordered[] = $item;
		}
	}
	ksort( $ordered );

	return array_values( $ordered );
}

/**
 * Save repeater items in the exact JetEngine `item-N` shape.
 *
 * @param int   $post_id Post ID.
 * @param string $key    Meta key.
 * @param array<int, array<string, mixed>> $items List of item arrays.
 */
function erfanpro_update_repeater( $post_id, $key, $items ) {
	$shaped = array();
	$i      = 0;
	foreach ( (array) $items as $item ) {
		if ( ! is_array( $item ) ) {
			continue;
		}
		// Drop fully-empty rows.
		$filled = array_filter(
			$item,
			function ( $v ) {
				return '' !== $v && null !== $v;
			}
		);
		if ( empty( $filled ) ) {
			continue;
		}
		$shaped[ 'item-' . $i ] = $item;
		$i++;
	}

	if ( empty( $shaped ) ) {
		// Keep the key present (JetEngine convention) with an empty value.
		update_post_meta( $post_id, $key, array() );
		return;
	}
	update_post_meta( $post_id, $key, $shaped );
}

/**
 * Read an ID list field (galleries, multi-selects).
 *
 * Accepts comma-separated strings ("12709,6297"), JSON arrays
 * (["26","31"]) and serialized PHP arrays. Returns clean ints.
 *
 * @param int    $post_id Post ID.
 * @param string $key     Meta key.
 * @return int[]
 */
function erfanpro_get_id_list( $post_id, $key ) {
	$raw = get_post_meta( $post_id, $key, true );

	if ( is_string( $raw ) ) {
		$maybe = maybe_unserialize( $raw );
		if ( is_array( $maybe ) ) {
			$raw = $maybe;
		} else {
			$decoded = json_decode( $raw, true );
			if ( is_array( $decoded ) ) {
				$raw = $decoded;
			} else {
				$raw = explode( ',', $raw );
			}
		}
	}

	if ( ! is_array( $raw ) ) {
		return array();
	}

	$ids = array();
	foreach ( $raw as $v ) {
		$id = absint( $v );
		if ( $id > 0 ) {
			$ids[] = $id;
		}
	}

	return array_values( array_unique( $ids ) );
}

/**
 * Save an ID list as a comma-separated string (gallery shape:
 * `galerry_produ`, `gallery-filed`, `gallery-projects`).
 *
 * @param int    $post_id Post ID.
 * @param string $key     Meta key.
 * @param array  $ids     Attachment/post IDs.
 */
function erfanpro_update_csv_ids( $post_id, $key, $ids ) {
	$clean = array();
	foreach ( (array) $ids as $v ) {
		$id = absint( $v );
		if ( $id > 0 ) {
			$clean[] = $id;
		}
	}
	$clean = array_values( array_unique( $clean ) );

	if ( empty( $clean ) ) {
		update_post_meta( $post_id, $key, '' );
		return;
	}
	update_post_meta( $post_id, $key, implode( ',', $clean ) );
}

/**
 * Save an ID list as a PHP array (multi-select shape: `project-ids`,
 * `usage_countr`, `mortabet_mahsole_2`, `product-in-projects`).
 * WordPress serializes it — the same shape JetEngine writes.
 *
 * @param int    $post_id Post ID.
 * @param string $key     Meta key.
 * @param array  $ids     Post/term IDs (stored as strings, like JetEngine).
 */
function erfanpro_update_id_array( $post_id, $key, $ids ) {
	$clean = array();
	foreach ( (array) $ids as $v ) {
		$id = absint( $v );
		if ( $id > 0 ) {
			$clean[] = (string) $id;
		}
	}
	$clean = array_values( array_unique( $clean ) );

	if ( empty( $clean ) ) {
		update_post_meta( $post_id, $key, array() );
		return;
	}
	update_post_meta( $post_id, $key, $clean );
}

/**
 * Sanitize repeater rows coming from $_POST.
 *
 * @param mixed $rows   Raw rows array.
 * @param array $fields Map of sub-field name => sanitizer
 *                      ('text'|'textarea'|'html'|'url'|'int'|'id').
 * @return array<int, array<string, mixed>>
 */
function erfanpro_sanitize_repeater_input( $rows, $fields ) {
	$out = array();
	if ( ! is_array( $rows ) ) {
		return $out;
	}
	foreach ( $rows as $row ) {
		if ( ! is_array( $row ) ) {
			continue;
		}
		$clean_row = array();
		foreach ( $fields as $name => $type ) {
			$raw = isset( $row[ $name ] ) ? $row[ $name ] : '';
			switch ( $type ) {
				case 'html':
					$clean_row[ $name ] = wp_kses_post( (string) $raw );
					break;
				case 'url':
					$clean_row[ $name ] = esc_url_raw( (string) $raw );
					break;
				case 'int':
				case 'id':
					$clean_row[ $name ] = absint( $raw );
					break;
				case 'textarea':
					$clean_row[ $name ] = sanitize_textarea_field( (string) $raw );
					break;
				case 'text':
				default:
					$clean_row[ $name ] = sanitize_text_field( (string) $raw );
					break;
			}
		}
		$out[] = $clean_row;
	}
	return $out;
}

/**
 * Pull one sub-value out of every repeater item (e.g. all questions).
 *
 * @param array  $items Repeater items.
 * @param string $field Sub-field name.
 * @return array
 */
function erfanpro_repeater_column( $items, $field ) {
	$col = array();
	foreach ( (array) $items as $item ) {
		if ( is_array( $item ) && isset( $item[ $field ] ) && '' !== $item[ $field ] ) {
			$col[] = $item[ $field ];
		}
	}
	return $col;
}
