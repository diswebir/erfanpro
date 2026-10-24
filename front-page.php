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

get_template_part( 'template-parts/home/hero' );
get_template_part( 'template-parts/home/marquee' );
get_template_part( 'template-parts/home/projects' );
get_template_part( 'template-parts/home/about' );
get_template_part( 'template-parts/home/products' );
get_template_part( 'template-parts/home/services' );
get_template_part( 'template-parts/home/why-us' );
get_template_part( 'template-parts/home/cta' );

get_footer();
