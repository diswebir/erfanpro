<?php
/**
 * Footer — phase 2: one-to-one with the static reference.
 *
 * Column data sources: about text + socials + contact from the theme
 * options (erfanpro_options, decision 27); quick links from the
 * 'mega' menu; services from the services CPT; featured products
 * from WooCommerce; trust badges from the options repeater
 * (BO-001) — shown in the bottom bar only when configured, so an
 * unconfigured footer stays pixel-identical to the reference.
 * Fallback titles below are the reference's own, used only until
 * real services/products exist.
 *
 * @package Erfanpro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$erfanpro_footer_about = (string) erfanpro_option( 'footer_about', 'شرکت دانش‌بنیان عرفان صنعت اصفهان؛ متخصص نورپردازی شهری و تولید انواع المان‌های نورانی با بیش از دو دهه تجربه. ارائه‌دهنده راهکارهای نوین و کاربردی برای طراحی و اجرای پروژه‌های نورپردازی در سراسر کشور.' );
$erfanpro_email        = (string) erfanpro_option( 'email', 'info@erfansanat.com' );
$erfanpro_address      = (string) erfanpro_option( 'address', 'اصفهان، خمینی‌شهر، شهرک صنعتی برق و الکترونیک، پلاک ۱۱۹' );

$erfanpro_socials = array(
	'instagram' => array(
		'label' => __( 'اینستاگرام', 'erfanpro' ),
		'icon'  => '<path d="M13.997 4a2 2 0 0 1 1.76 1.05l.486.9A2 2 0 0 0 18.003 7H20a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V9a2 2 0 0 1 2-2h1.997a2 2 0 0 0 1.759-1.048l.489-.904A2 2 0 0 1 10.004 4z"></path><circle cx="12" cy="13" r="3"></circle>',
	),
	'telegram'  => array(
		'label' => __( 'تلگرام', 'erfanpro' ),
		'icon'  => '<path d="M14.536 21.686a.5.5 0 0 0 .937-.024l6.5-19a.496.496 0 0 0-.635-.635l-19 6.5a.5.5 0 0 0-.024.937l7.93 3.18a2 2 0 0 1 1.112 1.11z"></path><path d="m21.854 2.147-10.94 10.939"></path>',
	),
	'aparat'    => array(
		'label' => __( 'آپارات', 'erfanpro' ),
		'icon'  => '<polygon points="6 3 20 12 6 21 6 3"></polygon>',
	),
	'linkedin'  => array(
		'label' => __( 'لینکدین', 'erfanpro' ),
		'icon'  => '<path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"></path><rect width="4" height="12" x="2" y="9"></rect><circle cx="4" cy="4" r="2"></circle>',
	),
);

$erfanpro_service_links = array();
foreach ( get_posts(
	array(
		'post_type'      => 'services',
		'post_status'    => 'publish',
		'posts_per_page' => 5,
		'orderby'        => array(
			'menu_order' => 'ASC',
			'date'       => 'ASC',
		),
	)
) as $erfanpro_service ) {
	$erfanpro_service_links[] = array( get_the_title( $erfanpro_service ), get_permalink( $erfanpro_service ) );
}
if ( ! $erfanpro_service_links ) {
	$erfanpro_services_archive = get_post_type_archive_link( 'services' );
	foreach ( array( 'نورپردازی میدان', 'نورپردازی خیابان', 'نورپردازی پل', 'المان‌های شهری', 'نورپردازی ساختمان' ) as $erfanpro_title ) {
		$erfanpro_service_links[] = array( $erfanpro_title, $erfanpro_services_archive ? $erfanpro_services_archive : home_url( '/services/' ) );
	}
}

$erfanpro_product_links = array();
if ( class_exists( 'WooCommerce' ) ) {
	$erfanpro_products = wc_get_products(
		array(
			'status'   => 'publish',
			'limit'    => 5,
			'featured' => true,
		)
	);
	if ( ! $erfanpro_products ) {
		$erfanpro_products = wc_get_products(
			array(
				'status'  => 'publish',
				'limit'   => 5,
				'orderby' => 'date',
				'order'   => 'DESC',
			)
		);
	}
	foreach ( (array) $erfanpro_products as $erfanpro_product ) {
		$erfanpro_product_links[] = array( $erfanpro_product->get_name(), $erfanpro_product->get_permalink() );
	}
}
if ( ! $erfanpro_product_links ) {
	$erfanpro_shop_url = function_exists( 'wc_get_page_id' ) && wc_get_page_id( 'shop' ) > 0
		? get_permalink( wc_get_page_id( 'shop' ) )
		: home_url( '/products/' );
	foreach ( array( 'درخت نوری آرتام', 'خورشید‌نما ۴۸ پره', 'لوستر نوری فروغ', 'ریسه فندقی فول‌کالر', 'پوینت لایت پیکسل' ) as $erfanpro_title ) {
		$erfanpro_product_links[] = array( $erfanpro_title, $erfanpro_shop_url );
	}
}

$erfanpro_badges = erfanpro_trust_badges();
?>
<footer class="relative z-10 border-t border-emerald-100 bg-white">
	<div class="mx-auto max-w-7xl px-5 pb-10 pt-16 lg:px-8 lg:pt-20">
		<div class="grid gap-12 lg:grid-cols-12">
			<div class="flex flex-col gap-6 lg:col-span-4">
				<?php get_template_part( 'template-parts/header/logo', null, array( 'link_class' => 'flex items-center' ) ); ?>
				<p class="max-w-sm text-[13.5px] leading-8 text-slate-600"><?php echo esc_html( $erfanpro_footer_about ); ?></p>
				<?php
				$erfanpro_has_social = false;
				foreach ( $erfanpro_socials as $erfanpro_key => $erfanpro_social ) {
					if ( erfanpro_option( $erfanpro_key, '' ) ) {
						$erfanpro_has_social = true;
						break;
					}
				}
				if ( $erfanpro_has_social ) :
					?>
				<div class="flex items-center gap-3">
					<?php
					foreach ( $erfanpro_socials as $erfanpro_key => $erfanpro_social ) :
						$erfanpro_url = (string) erfanpro_option( $erfanpro_key, '' );
						if ( ! $erfanpro_url ) {
							continue;
						}
						?>
					<a href="<?php echo esc_url( $erfanpro_url ); ?>" target="_blank" rel="noopener" aria-label="<?php echo esc_attr( $erfanpro_social['label'] ); ?>" class="grid h-10 w-10 place-items-center rounded-xl border border-slate-200 bg-slate-50 text-slate-600 transition-all duration-400 hover:-translate-y-1 hover:border-emerald-400 hover:bg-emerald-50 hover:text-emerald-700">
						<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide h-4 w-4" aria-hidden="true"><?php echo $erfanpro_social['icon']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static inline SVG paths defined above. ?></svg>
					</a>
					<?php endforeach; ?>
					<span class="mr-2 text-[12px] text-slate-500"><?php esc_html_e( 'ما را دنبال کنید', 'erfanpro' ); ?></span>
				</div>
				<?php endif; ?>
			</div>
			<div class="lg:col-span-2">
				<h4 class="mb-5 flex items-center gap-2 text-[15px] font-extrabold text-slate-900"><span class="h-4 w-1 rounded-full bg-gradient-to-b from-emerald-500 to-emerald-700"></span><?php esc_html_e( 'دسترسی سریع', 'erfanpro' ); ?></h4>
				<ul class="flex flex-col gap-3">
					<?php erfanpro_render_footer_quick_links(); ?>
				</ul>
			</div>
			<div class="lg:col-span-2">
				<h4 class="mb-5 flex items-center gap-2 text-[15px] font-extrabold text-slate-900"><span class="h-4 w-1 rounded-full bg-gradient-to-b from-emerald-500 to-emerald-700"></span><?php esc_html_e( 'خدمات نورپردازی', 'erfanpro' ); ?></h4>
				<ul class="flex flex-col gap-3">
					<?php foreach ( $erfanpro_service_links as $erfanpro_link ) : ?>
					<li><a class="group flex items-center gap-2 text-[13.5px] text-slate-600 transition-colors duration-300 hover:text-emerald-700" href="<?php echo esc_url( $erfanpro_link[1] ); ?>"><span class="h-1 w-1 rounded-full bg-slate-400 transition-all duration-300 group-hover:w-2.5 group-hover:bg-emerald-600"></span><?php echo esc_html( $erfanpro_link[0] ); ?></a></li>
					<?php endforeach; ?>
				</ul>
			</div>
			<div class="lg:col-span-2">
				<h4 class="mb-5 flex items-center gap-2 text-[15px] font-extrabold text-slate-900"><span class="h-4 w-1 rounded-full bg-gradient-to-b from-emerald-500 to-emerald-700"></span><?php esc_html_e( 'محصولات برگزیده', 'erfanpro' ); ?></h4>
				<ul class="flex flex-col gap-3">
					<?php foreach ( $erfanpro_product_links as $erfanpro_link ) : ?>
					<li><a class="group flex items-center gap-2 text-[13.5px] text-slate-600 transition-colors duration-300 hover:text-emerald-700" href="<?php echo esc_url( $erfanpro_link[1] ); ?>"><span class="h-1 w-1 rounded-full bg-slate-400 transition-all duration-300 group-hover:w-2.5 group-hover:bg-emerald-600"></span><?php echo esc_html( $erfanpro_link[0] ); ?></a></li>
					<?php endforeach; ?>
				</ul>
			</div>
			<div class="lg:col-span-2">
				<h4 class="mb-5 flex items-center gap-2 text-[15px] font-extrabold text-slate-900"><span class="h-4 w-1 rounded-full bg-gradient-to-b from-emerald-500 to-emerald-700"></span><?php esc_html_e( 'تماس با ما', 'erfanpro' ); ?></h4>
				<ul class="flex flex-col gap-4 text-[13px] leading-6 text-slate-600">
					<li class="flex items-start gap-2.5">
						<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-phone mt-0.5 h-4 w-4 shrink-0 text-emerald-700" aria-hidden="true"><path d="M13.832 16.568a1 1 0 0 0 1.213-.303l.355-.465A2 2 0 0 1 17 15h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2A18 18 0 0 1 2 4a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v3a2 2 0 0 1-.8 1.6l-.468.351a1 1 0 0 0-.292 1.233 14 14 0 0 0 6.392 6.384"></path></svg>
						<a href="tel:<?php echo esc_attr( erfanpro_phone_tel() ); ?>" class="transition-colors hover:text-emerald-700"><?php echo esc_html( erfanpro_phone_display() ); ?><span class="block text-[11.5px] text-slate-500">۱۰ خط ویژه فروش</span></a>
					</li>
					<li class="flex items-start gap-2.5">
						<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-mail mt-0.5 h-4 w-4 shrink-0 text-emerald-700" aria-hidden="true"><path d="m22 7-8.991 5.727a2 2 0 0 1-2.009 0L2 7"></path><rect x="2" y="4" width="20" height="16" rx="2"></rect></svg>
						<a href="mailto:<?php echo esc_attr( $erfanpro_email ); ?>" dir="ltr" class="transition-colors hover:text-emerald-700"><?php echo esc_html( $erfanpro_email ); ?></a>
					</li>
					<li class="flex items-start gap-2.5">
						<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map-pin mt-0.5 h-4 w-4 shrink-0 text-emerald-700" aria-hidden="true"><path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"></path><circle cx="12" cy="10" r="3"></circle></svg>
						<span><?php echo esc_html( $erfanpro_address ); ?></span>
					</li>
				</ul>
			</div>
		</div>
		<div class="mt-14 flex flex-col items-center justify-between gap-5 border-t border-slate-200 pt-8 sm:flex-row">
			<p class="text-[12.5px] text-slate-500">© ۱۴۰۴ — تمامی حقوق متعلق به <span class="font-bold text-slate-700">شرکت دانش‌بنیان عرفان صنعت اصفهان</span> می‌باشد.</p>
			<?php if ( $erfanpro_badges ) : ?>
			<div class="flex items-center gap-3">
				<?php
				foreach ( $erfanpro_badges as $erfanpro_badge ) :
					$erfanpro_badge_img = ! empty( $erfanpro_badge['image'] )
						? wp_get_attachment_image(
							(int) $erfanpro_badge['image'],
							'full',
							false,
							array(
								'class' => 'h-10',
								'style' => 'width:auto',
								'alt'   => (string) ( $erfanpro_badge['label'] ?? '' ),
							)
						)
						: '';
					if ( ! $erfanpro_badge_img ) {
						continue;
					}
					?>
					<?php if ( ! empty( $erfanpro_badge['url'] ) ) : ?>
					<a href="<?php echo esc_url( $erfanpro_badge['url'] ); ?>" target="_blank" rel="noopener"><?php echo $erfanpro_badge_img; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- wp_get_attachment_image output. ?></a>
					<?php else : ?>
					<?php echo $erfanpro_badge_img; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- wp_get_attachment_image output. ?>
					<?php endif; ?>
				<?php endforeach; ?>
			</div>
			<?php endif; ?>
			<button type="button" data-to-top class="group flex items-center gap-2.5 rounded-full border border-slate-200 px-5 py-2.5 text-[12.5px] font-bold text-slate-700 transition-all duration-400 hover:border-emerald-500 hover:text-emerald-700"><?php esc_html_e( 'بازگشت به بالا', 'erfanpro' ); ?><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-up h-4 w-4 transition-transform duration-400 group-hover:-translate-y-1" aria-hidden="true"><path d="m5 12 7-7 7 7"></path><path d="M12 19V5"></path></svg></button>
		</div>
	</div>
</footer>
<?php get_template_part( 'template-parts/header/floating-buttons' ); ?>
</div>
<?php wp_footer(); ?>
</body>
</html>
