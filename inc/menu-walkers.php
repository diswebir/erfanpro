<?php
/**
 * Phase 2 — menu rendering for the global header/footer.
 *
 * The desktop "mega" navigation and the footer link lists are rendered
 * with wp_nav_menu() on the registered theme locations (blueprint menus:
 * main-mega-menu, فهرست موبایل, landing, tolidat-sherkat), but with custom
 * walkers that reproduce the static reference markup one-to-one (the
 * reference uses flat <a> pills in the header and <li><a> rows with a dot
 * span in the footer — a stock <ul>/<li> header menu would break parity
 * with the compiled reference CSS, which carries no <li> styling there).
 *
 * If a location has no menu assigned yet, the blueprint menu of the same
 * name is picked up automatically (the live database already contains
 * those menus); if none exists either, a fallback mirroring the
 * reference links is rendered so the shell always works.
 *
 * @package Erfanpro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Resolve the menu id for a location: the assigned menu first, then a
 * blueprint menu looked up by name/slug.
 *
 * @param string   $location   Theme location slug.
 * @param string[] $candidates Blueprint menu names/slugs to try.
 * @return int Menu term id, 0 when nothing found.
 */
function erfanpro_menu_id_for( $location, $candidates = array() ) {
	$locations = get_nav_menu_locations();
	if ( ! empty( $locations[ $location ] ) ) {
		return (int) $locations[ $location ];
	}
	foreach ( $candidates as $candidate ) {
		$menu = wp_get_nav_menu_object( $candidate );
		if ( $menu ) {
			return (int) $menu->term_id;
		}
	}
	return 0;
}

/**
 * The reference default navigation (labels + site URLs), used only as a
 * fallback when no WordPress menu exists yet.
 *
 * @return array<int, array{0:string,1:string}> [label, url] pairs.
 */
function erfanpro_default_nav_items() {
	$posts_page = (int) get_option( 'page_for_posts' );
	$blog_url   = $posts_page ? get_permalink( $posts_page ) : home_url( '/blog/' );

	$shop_url = home_url( '/products/' );
	if ( function_exists( 'wc_get_page_id' ) && wc_get_page_id( 'shop' ) > 0 ) {
		$shop_url = get_permalink( wc_get_page_id( 'shop' ) );
	}

	$about_page   = get_page_by_path( 'about' );
	$contact_page = get_page_by_path( 'contact' );

	return array(
		array( __( 'خانه', 'erfanpro' ), home_url( '/' ) ),
		array( __( 'پروژه‌ها', 'erfanpro' ), get_post_type_archive_link( 'projects' ) ?: home_url( '/projects/' ) ),
		array( __( 'محصولات', 'erfanpro' ), $shop_url ),
		array( __( 'خدمات', 'erfanpro' ), get_post_type_archive_link( 'services' ) ?: home_url( '/services/' ) ),
		array( __( 'وبلاگ', 'erfanpro' ), $blog_url ? $blog_url : home_url( '/blog/' ) ),
		array( __( 'درباره ما', 'erfanpro' ), $about_page ? get_permalink( $about_page ) : home_url( '/about/' ) ),
		array( __( 'تماس با ما', 'erfanpro' ), $contact_page ? get_permalink( $contact_page ) : home_url( '/contact/' ) ),
	);
}

/**
 * Whether a URL matches the current request URL (fallback active state).
 *
 * @param string $url URL to test.
 * @return bool
 */
function erfanpro_is_current_url( $url ) {
	global $wp;
	$request = isset( $wp->request ) ? (string) $wp->request : '';
	$current = home_url( '' !== $request ? '/' . $request : '/' );
	return trailingslashit( (string) $url ) === trailingslashit( $current );
}

/**
 * Header desktop-nav walker: flat reference pill anchors, no <ul>/<li>.
 */
class Erfanpro_Nav_Walker extends Walker_Nav_Menu {

	/**
	 * No sub-level wrappers — the reference header nav is flat.
	 *
	 * @param string $output Output.
	 * @param int    $depth  Depth.
	 * @param array  $args   Args.
	 */
	public function start_lvl( &$output, $depth = 0, $args = null ) {
	}

	/**
	 * No sub-level wrappers.
	 *
	 * @param string $output Output.
	 * @param int    $depth  Depth.
	 * @param array  $args   Args.
	 */
	public function end_lvl( &$output, $depth = 0, $args = null ) {
	}

	/**
	 * One pill anchor, byte-identical classes to the reference.
	 *
	 * @param string $output            Output.
	 * @param object $item              Menu item.
	 * @param int    $depth             Depth.
	 * @param array  $args              Args.
	 * @param int    $current_object_id Current object id.
	 */
	public function start_el( &$output, $item, $depth = 0, $args = null, $current_object_id = 0 ) {
		$classes = (array) $item->classes;
		$current = in_array( 'current-menu-item', $classes, true )
			|| in_array( 'current_page_item', $classes, true )
			|| in_array( 'current-menu-ancestor', $classes, true );

		$class = $current
			? 'relative rounded-full px-3.5 py-2 text-[13.5px] font-bold transition-colors duration-300 bg-emerald-50 text-emerald-700'
			: 'relative rounded-full px-3.5 py-2 text-[13.5px] font-bold transition-colors duration-300 text-slate-700 hover:text-emerald-700';

		$output .= sprintf(
			'<a%s class="%s" href="%s">%s</a>',
			$current ? ' aria-current="page"' : '',
			esc_attr( $class ),
			esc_url( $item->url ),
			esc_html( $item->title )
		);
	}

	/**
	 * Anchors are not wrapped in <li>.
	 *
	 * @param string $output Output.
	 * @param object $item   Menu item.
	 * @param int    $depth  Depth.
	 * @param array  $args   Args.
	 */
	public function end_el( &$output, $item, $depth = 0, $args = null ) {
	}
}

/**
 * Footer link-list walker: <li><a><span dot></span>Title</a></li> rows,
 * byte-identical to the reference footer columns.
 */
class Erfanpro_Footer_Links_Walker extends Walker_Nav_Menu {

	/**
	 * No sub-level wrappers.
	 *
	 * @param string $output Output.
	 * @param int    $depth  Depth.
	 * @param array  $args   Args.
	 */
	public function start_lvl( &$output, $depth = 0, $args = null ) {
	}

	/**
	 * No sub-level wrappers.
	 *
	 * @param string $output Output.
	 * @param int    $depth  Depth.
	 * @param array  $args   Args.
	 */
	public function end_lvl( &$output, $depth = 0, $args = null ) {
	}

	/**
	 * One footer link row.
	 *
	 * @param string $output            Output.
	 * @param object $item              Menu item.
	 * @param int    $depth             Depth.
	 * @param array  $args              Args.
	 * @param int    $current_object_id Current object id.
	 */
	public function start_el( &$output, $item, $depth = 0, $args = null, $current_object_id = 0 ) {
		$output .= sprintf(
			'<li><a class="group flex items-center gap-2 text-[13.5px] text-slate-600 transition-colors duration-300 hover:text-emerald-700" href="%s"><span class="h-1 w-1 rounded-full bg-slate-400 transition-all duration-300 group-hover:w-2.5 group-hover:bg-emerald-600"></span>%s</a>',
			esc_url( $item->url ),
			esc_html( $item->title )
		);
	}

	/**
	 * Close the row.
	 *
	 * @param string $output Output.
	 * @param object $item   Menu item.
	 * @param int    $depth  Depth.
	 * @param array  $args   Args.
	 */
	public function end_el( &$output, $item, $depth = 0, $args = null ) {
		$output .= '</li>';
	}
}

/**
 * Render the header desktop nav items (inside the <nav> printed by the
 * header template part): 'mega' location via wp_nav_menu, top level
 * only — the reference header nav is flat (decision: visual reference
 * has priority). Falls back to the reference default links.
 */
function erfanpro_render_main_nav() {
	$menu_id = erfanpro_menu_id_for( 'mega', array( 'main-mega-menu' ) );
	if ( $menu_id ) {
		wp_nav_menu(
			array(
				'menu'        => $menu_id,
				'container'   => false,
				'items_wrap'  => '%3$s',
				'depth'       => 1,
				'walker'      => new Erfanpro_Nav_Walker(),
				'fallback_cb' => false,
			)
		);
		return;
	}

	foreach ( erfanpro_default_nav_items() as $nav_item ) {
		$current = erfanpro_is_current_url( $nav_item[1] );
		$class   = $current
			? 'relative rounded-full px-3.5 py-2 text-[13.5px] font-bold transition-colors duration-300 bg-emerald-50 text-emerald-700'
			: 'relative rounded-full px-3.5 py-2 text-[13.5px] font-bold transition-colors duration-300 text-slate-700 hover:text-emerald-700';
		printf(
			'<a%s class="%s" href="%s">%s</a>',
			$current ? ' aria-current="page"' : '',
			esc_attr( $class ),
			esc_url( $nav_item[1] ),
			esc_html( $nav_item[0] )
		);
	}
}

/**
 * Render the footer "quick access" rows (inside the <ul> printed by
 * footer.php): same 'mega' menu, footer walker; reference fallback.
 */
function erfanpro_render_footer_quick_links() {
	$menu_id = erfanpro_menu_id_for( 'mega', array( 'main-mega-menu' ) );
	if ( $menu_id ) {
		wp_nav_menu(
			array(
				'menu'        => $menu_id,
				'container'   => false,
				'items_wrap'  => '%3$s',
				'depth'       => 1,
				'walker'      => new Erfanpro_Footer_Links_Walker(),
				'fallback_cb' => false,
			)
		);
		return;
	}

	foreach ( erfanpro_default_nav_items() as $nav_item ) {
		printf(
			'<li><a class="group flex items-center gap-2 text-[13.5px] text-slate-600 transition-colors duration-300 hover:text-emerald-700" href="%s"><span class="h-1 w-1 rounded-full bg-slate-400 transition-all duration-300 group-hover:w-2.5 group-hover:bg-emerald-600"></span>%s</a></li>',
			esc_url( $nav_item[1] ),
			esc_html( $nav_item[0] )
		);
	}
}

/**
 * Render the hidden mobile-menu source list.
 *
 * The reference runtime (assets/js/main.js, initMobileMenu) builds the
 * animated mobile panel by cloning the anchors of the FIRST <nav>
 * inside <header> plus the header tel: link. That is why the header
 * prints this visually-hidden source nav (the 'mobile' location —
 * blueprint menu «فهرست موبایل») before the desktop nav: the panel then
 * carries the full mobile menu, exactly like the reference behaviour.
 * Falls back to the 'mega' menu, then to the reference default links.
 */
function erfanpro_render_mobile_source() {
	$menu_id = erfanpro_menu_id_for( 'mobile', array( 'فهرست موبایل', 'mobile-menu' ) );
	if ( ! $menu_id ) {
		$menu_id = erfanpro_menu_id_for( 'mega', array( 'main-mega-menu' ) );
	}
	if ( $menu_id ) {
		wp_nav_menu(
			array(
				'menu'        => $menu_id,
				'container'   => false,
				'depth'       => 0,
				'fallback_cb' => false,
			)
		);
		return;
	}

	echo '<ul>';
	foreach ( erfanpro_default_nav_items() as $nav_item ) {
		printf(
			'<li><a href="%s">%s</a></li>',
			esc_url( $nav_item[1] ),
			esc_html( $nav_item[0] )
		);
	}
	echo '</ul>';
}
