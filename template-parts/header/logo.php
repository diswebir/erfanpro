<?php
/**
 * Brand logo — shared by the header and the footer.
 *
 * If a custom logo is set in the Customizer it replaces the bundled
 * ESE mark; otherwise the reference inline SVG + wordmark is printed,
 * byte-identical to the static reference.
 *
 * @package Erfanpro
 *
 * @var array $args { link_class: string } Anchor classes (header/footer differ).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$erfanpro_logo_link_class = isset( $args['link_class'] ) ? (string) $args['link_class'] : 'group flex items-center';
?>
<a class="<?php echo esc_attr( $erfanpro_logo_link_class ); ?>" href="<?php echo esc_url( home_url( '/' ) ); ?>">
	<span class="inline-flex items-center gap-3">
		<span class="relative inline-flex shrink-0 items-center justify-center px-2 py-1">
			<?php if ( has_custom_logo() ) : ?>
				<?php
				echo wp_get_attachment_image(
					(int) get_theme_mod( 'custom_logo' ),
					'full',
					false,
					array(
						'class' => 'h-11',
						'style' => 'width:auto',
						'alt'   => get_bloginfo( 'name' ),
					)
				);
				?>
			<?php else : ?>
			<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 540 200" fill="none" class="h-11 w-[7.5rem]" aria-label="<?php esc_attr_e( 'لوگوی عرفان صنعت اصفهان ESE ERFAN', 'erfanpro' ); ?>"><path d="M100 79 L172 79 C248 56 346 46 398 63 C426 72 429 95 407 112 C442 97 451 65 414 45 C358 16 226 35 100 79 Z" fill="#00A83E"></path><path d="M78 96 C44 121 42 158 86 174 C150 197 280 176 396 135 L330 135 C244 156 148 166 98 150 C65 140 61 115 78 96 Z" fill="#A8B0B8"></path><g transform="translate(106, 0) skewX(-14)"><path d="M0 86 H96 V97 H35 V119 H96 V130 H0 V120 H11 V96 H0 V86 Z" fill="#00A83E"></path><rect x="41" y="103" width="37" height="10" fill="#00A83E"></rect><path d="M108 100 C108 90 116 86 130 86 H202 V97 H135 C130 97 128 99 128 101 C128 103 130 104 136 104 H182 C196 104 203 109 203 118 C203 126 196 130 181 130 H108 V119 H176 C181 119 183 118 183 115 C183 113 181 112 175 112 H129 C114 112 108 108 108 100 Z" fill="#00A83E"></path><path d="M215 86 H311 V97 H250 V119 H311 V130 H215 V120 H226 V96 H215 V86 Z" fill="#B4BCC4"></path><rect x="256" y="103" width="37" height="10" fill="#B4BCC4"></rect></g><text x="393" y="130" fill="#B4BCC4" font-family="Georgia, 'Times New Roman', serif" font-weight="900" font-style="italic" font-size="33" letter-spacing="1.5">RFAN</text></svg>
			<?php endif; ?>
		</span>
		<span class="flex flex-col leading-tight">
			<span class="text-[16.5px] font-extrabold text-slate-900"><?php esc_html_e( 'عرفان', 'erfanpro' ); ?> <span class="text-emerald-600"><?php esc_html_e( 'صنعت', 'erfanpro' ); ?></span> <?php esc_html_e( 'اصفهان', 'erfanpro' ); ?></span>
			<span class="text-[10.5px] font-bold tracking-[0.14em] text-slate-500">ESE · ERFAN SANAT</span>
		</span>
	</span>
</a>
