<?php
/**
 * Front page — phase 3: the home page, section by section, pixel-cloned
 * from the static reference (~/workspace/main-erfansanat-static/site/).
 *
 * @package Erfanpro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

/* Section manager: order/visibility from the theme options panel. */
erfanpro_render_page_sections(
	'home',
	array(
		'hero'     => 'template-parts/home/hero',
		'marquee'  => 'template-parts/home/marquee',
		'projects' => 'template-parts/home/projects',
		'about'    => 'template-parts/home/about',
		'products' => 'template-parts/home/products',
		'services' => 'template-parts/home/services',
		'why_us'   => 'template-parts/home/why-us',
		'cta'      => 'template-parts/home/cta',
	)
);

get_footer();
