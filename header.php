<?php
/**
 * Header — phase 2: the reference page shell (background decorations)
 * plus the global header (template-parts/header/header-main.php:
 * mobile-menu source, logo, mega nav, language switcher, search,
 * phone pill, hamburger).
 *
 * The <html> `anim` class + guard script replicate the reference
 * head exactly: anim.css gates the reveal/navbar animations behind
 * it, and the script removes it if anim.js fails to initialise.
 *
 * @package Erfanpro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<script>document.documentElement.classList.add('anim');setTimeout(function(){if(!window.__animInit)document.documentElement.classList.remove('anim')},2600);</script>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div class="relative min-h-screen bg-[#f6f8f7] text-slate-800">
	<div class="pointer-events-none fixed inset-0 z-0 overflow-hidden" aria-hidden="true"><div class="absolute -top-36 right-[5%] h-[36rem] w-[36rem] rounded-full bg-emerald-200/40 blur-[130px]"></div><div class="absolute top-[32%] -left-32 h-[32rem] w-[32rem] rounded-full bg-teal-100/50 blur-[130px]"></div><div class="absolute bottom-[10%] right-[18%] h-[34rem] w-[34rem] rounded-full bg-green-100/60 blur-[140px]"></div><div class="light-dot-grid absolute inset-0 opacity-60"></div></div>
	<?php get_template_part( 'template-parts/header/header-main' ); ?>
