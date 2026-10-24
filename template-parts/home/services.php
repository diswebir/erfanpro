<?php
/**
 * Home — services (phase 3).
 *
 * Five `services` CPT items as an accordion: the left column lists the
 * services, the sticky desktop panel previews the active one (image,
 * icon, title, description, «۱ / ۵» counter). Switching is handled by
 * assets/js/home.js (click). The reference static services are the
 * design fallback when the CPT is empty.
 *
 * @package Erfanpro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$erfanpro_services = array();
if ( post_type_exists( 'services' ) ) {
	$q = new WP_Query(
		array(
			'post_type'      => 'services',
			'post_status'    => 'publish',
			'posts_per_page' => 5,
			'orderby'        => array( 'menu_order' => 'ASC', 'date' => 'ASC' ),
			'no_found_rows'  => true,
		)
	);
	if ( $q->have_posts() ) {
		while ( $q->have_posts() ) {
			$q->the_post();
			$desc = has_excerpt() ? get_the_excerpt() : '';
			if ( '' === $desc && function_exists( 'erfanpro_service_items' ) ) {
				$features = erfanpro_service_items( get_the_ID(), 'service_features' );
				if ( ! empty( $features[0]['desc'] ) ) {
					$desc = $features[0]['desc'];
				}
			}
			if ( '' === $desc ) {
				$desc = wp_trim_words( wp_strip_all_tags( get_the_content() ), 24 );
			}
			$img = get_the_post_thumbnail_url( get_the_ID(), 'large' );
			$erfanpro_services[] = array(
				'title' => get_the_title(),
				'desc'  => $desc,
				'image' => $img ? $img : '',
				'url'   => get_permalink(),
			);
		}
		wp_reset_postdata();
	}
}

/* Reference icons (lucide, inline — zero external requests). */
$erfanpro_svc_icons = array(
	'<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" data-svc-icon class="lucide lucide-landmark h-6 w-6 shrink-0 transition-all duration-500" aria-hidden="true"><path d="M10 18v-7"></path><path d="M11.119 2.205a2 2 0 0 1 1.762 0l7.84 3.846A.5.5 0 0 1 20.5 7h-17a.5.5 0 0 1-.22-.949z"></path><path d="M14 18v-7"></path><path d="M18 18v-7"></path><path d="M3 22h18"></path><path d="M6 18v-7"></path></svg>',
	'<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" data-svc-icon class="lucide lucide-milestone h-6 w-6 shrink-0 transition-all duration-500" aria-hidden="true"><path d="M12 13v8"></path><path d="M12 3v3"></path><path d="M18.172 6a2 2 0 0 1 1.414.586l2.06 2.06a1.207 1.207 0 0 1 0 1.708l-2.06 2.06a2 2 0 0 1-1.414.586H4a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1z"></path></svg>',
	'<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" data-svc-icon class="lucide lucide-waypoints h-6 w-6 shrink-0 transition-all duration-500" aria-hidden="true"><path d="m10.586 5.414-5.172 5.172"></path><path d="m18.586 13.414-5.172 5.172"></path><path d="M6 12h12"></path><circle cx="12" cy="20" r="2"></circle><circle cx="12" cy="4" r="2"></circle><circle cx="20" cy="12" r="2"></circle><circle cx="4" cy="12" r="2"></circle></svg>',
	'<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" data-svc-icon class="lucide lucide-sparkles lucide-stars h-6 w-6 shrink-0 transition-all duration-500" aria-hidden="true"><path d="M11.017 2.814a1 1 0 0 1 1.966 0l1.051 5.558a2 2 0 0 0 1.594 1.594l5.558 1.051a1 1 0 0 1 0 1.966l-5.558 1.051a2 2 0 0 0 1.594 1.594l-1.051 5.558a1 1 0 0 1-1.966 0l-1.051-5.558a2 2 0 0 0-1.594-1.594l-5.558-1.051a1 1 0 0 1 0-1.966l5.558-1.051a2 2 0 0 0 1.594-1.594z"></path><path d="M20 2v4"></path><path d="M22 4h-4"></path><circle cx="4" cy="20" r="2"></circle></svg>',
	'<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" data-svc-icon class="lucide lucide-building-complex lucide-building-2 h-6 w-6 shrink-0 transition-all duration-500" aria-hidden="true"><path d="M10 12h4"></path><path d="M10 8h4"></path><path d="M14 21v-3a2 2 0 0 0-4 0v3"></path><path d="M6 10H4a2 2 0 0 0-2 2v7a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-2"></path><path d="M6 21V5a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v16"></path></svg>',
);

/* Reference design fallback (5 static services). */
if ( count( $erfanpro_services ) < 5 ) {
	$erfanpro_services = array(
		array(
			'title' => 'نورپردازی میدان',
			'desc'  => 'خلق نقطه‌کانونی بصری در قلب شهر با المان‌ها و سازه‌های نوری باشکوه',
			'image' => ERFANPRO_URI . '/assets/img/light-sphere.jpg',
			'url'   => erfanpro_archive_url( 'services' ),
		),
		array(
			'title' => 'نورپردازی خیابان و بلوار',
			'desc'  => '',
			'image' => ERFANPRO_URI . '/assets/img/light-tunnel.jpg',
			'url'   => erfanpro_archive_url( 'services' ),
		),
		array(
			'title' => 'نورپردازی پل',
			'desc'  => '',
			'image' => ERFANPRO_URI . '/assets/img/light-element.jpg',
			'url'   => erfanpro_archive_url( 'services' ),
		),
		array(
			'title' => 'نورپردازی المان‌های شهری',
			'desc'  => '',
			'image' => ERFANPRO_URI . '/assets/img/sun-light.jpg',
			'url'   => erfanpro_archive_url( 'services' ),
		),
		array(
			'title' => 'نورپردازی ساختمان',
			'desc'  => '',
			'image' => ERFANPRO_URI . '/assets/img/chandelier.jpg',
			'url'   => erfanpro_archive_url( 'services' ),
		),
	);
}

$erfanpro_sec = erfanpro_home_section( 'services' );
$erfanpro_n  = count( $erfanpro_services );
?>
<section id="services" class="relative z-10 scroll-mt-20 py-24 lg:py-32">
	<div class="mx-auto max-w-7xl px-5 lg:px-8">
		<div class="flex max-w-3xl flex-col gap-4 items-center text-center mx-auto">
			<div<?php erfanpro_reveal( 0.00 ); ?>>
				<span class="inline-flex items-center gap-2 rounded-full border border-emerald-500/25 bg-emerald-50 px-4 py-1.5 text-[13px] font-bold tracking-wide text-emerald-800 shadow-xs">
					<span class="relative flex h-2 w-2">
						<span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-500 opacity-60"></span>
						<span class="relative inline-flex h-2 w-2 rounded-full bg-emerald-600"></span>
					</span><?php esc_html_e( 'خدمات حرفه‌ای', 'erfanpro' ); ?>
				</span>
			</div>
			<div<?php erfanpro_reveal( 0.07 ); ?>>
				<?php if ( '' !== $erfanpro_sec['title'] ) : ?>
				<h2 class="text-3xl font-extrabold leading-[1.35] text-slate-900 sm:text-4xl lg:text-[2.6rem] lg:leading-[1.3]"><?php echo esc_html( $erfanpro_sec['title'] ); ?></h2>
				<?php else : ?>
				<h2 class="text-3xl font-extrabold leading-[1.35] text-slate-900 sm:text-4xl lg:text-[2.6rem] lg:leading-[1.3]"><?php esc_html_e( 'خدمات', 'erfanpro' ); ?> <span class="bg-gradient-to-l from-emerald-800 via-emerald-600 to-teal-700 bg-clip-text text-transparent"><?php esc_html_e( 'نورپردازی شهری', 'erfanpro' ); ?></span></h2>
				<?php endif; ?>
			</div>
			<div<?php erfanpro_reveal( 0.14 ); ?>>
				<p class="text-base leading-8 text-slate-600 lg:text-[17px] lg:leading-9"><?php echo '' !== $erfanpro_sec['subtitle'] ? esc_html( $erfanpro_sec['subtitle'] ) : esc_html__( 'از میدان تا پل؛ راهکارهایی جامع برای شهرداری‌ها، سازمان‌های عمرانی و پیمانکاران پروژه‌های شهری', 'erfanpro' ); ?></p>
			</div>
		</div>
		<div class="mt-14 grid gap-8 lg:grid-cols-2 lg:gap-12" data-erfanpro-services>
			<div class="flex flex-col"<?php erfanpro_reveal( 0.00 ); ?>>
				<?php foreach ( $erfanpro_services as $i => $svc ) : ?>
					<?php
					$active      = 0 === $i;
					$icon        = $erfanpro_svc_icons[ $i % count( $erfanpro_svc_icons ) ];
					$btn_class   = 'group relative flex items-start gap-5 border-b border-slate-200 px-4 py-6 text-right transition-all duration-500 lg:py-7' . ( $active ? ' rounded-2xl bg-emerald-50' : ' hover:bg-slate-50' );
					$num_class   = 'text-sm font-black transition-colors duration-400' . ( $active ? ' text-emerald-700' : ' text-slate-400' );
					$title_class = 'text-lg font-extrabold transition-colors duration-400 lg:text-[1.4rem]' . ( $active ? ' text-emerald-950' : ' text-slate-800' );
					$bar_class   = 'absolute right-0 top-1/2 h-0 w-[3px] -translate-y-1/2 rounded-full bg-gradient-to-b from-emerald-500 to-emerald-700 transition-all duration-500' . ( $active ? ' h-3/4' : '' );
					?>
				<button type="button" class="<?php echo esc_attr( $btn_class ); ?>" data-svc-btn="<?php echo esc_attr( (string) $i ); ?>" data-svc-img="<?php echo esc_attr( $svc['image'] ); ?>" data-svc-name="<?php echo esc_attr( $svc['title'] ); ?>" data-svc-text="<?php echo esc_attr( $svc['desc'] ); ?>" aria-expanded="<?php echo $active ? 'true' : 'false'; ?>">
					<span class="<?php echo esc_attr( $bar_class ); ?>" data-svc-bar></span>
					<span class="<?php echo esc_attr( $num_class ); ?>" data-svc-num><?php echo esc_html( erfanpro_fa_digits( $i + 1 < 10 ? '0' . ( $i + 1 ) : (string) ( $i + 1 ) ) ); ?></span>
					<div class="flex flex-1 flex-col gap-1.5">
						<span class="flex items-center justify-between gap-3">
							<span class="<?php echo esc_attr( $title_class ); ?>" data-svc-title><?php echo esc_html( $svc['title'] ); ?></span>
							<span data-svc-iconwrap><?php echo str_replace( 'shrink-0 transition-all duration-500', 'shrink-0 transition-all duration-500 ' . ( $active ? 'scale-110 text-emerald-700' : 'text-slate-400 group-hover:text-emerald-600' ), $icon ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
						</span>
						<?php if ( '' !== $svc['desc'] ) : ?>
						<p class="overflow-hidden text-[13.5px] font-medium leading-7 text-slate-600" data-svc-desc<?php echo $active ? ' style="opacity: 1; height: auto;"' : ' style="display:none;"'; ?>><?php echo esc_html( $svc['desc'] ); ?></p>
						<?php endif; ?>
						<?php if ( $svc['image'] ) : ?>
						<div class="overflow-hidden lg:hidden" data-svc-mimg<?php echo $active ? ' style="opacity: 1; height: auto;"' : ' style="display:none;"'; ?>>
							<img alt="<?php echo esc_attr( $svc['title'] ); ?>" loading="lazy" class="mt-4 h-44 w-full rounded-2xl border border-emerald-100 object-cover" src="<?php echo esc_url( $svc['image'] ); ?>">
						</div>
						<?php endif; ?>
					</div>
				</button>
				<?php endforeach; ?>
			</div>
			<div class="relative hidden lg:block"<?php erfanpro_reveal( 0.07 ); ?> data-svc-preview>
				<div class="sticky top-28 overflow-hidden rounded-[2rem] border border-emerald-100 shadow-lg">
					<div class="relative h-[540px]"<?php erfanpro_reveal( 0.00, 'inview', 'opacity: 0; transform: scale(1.06);' ); ?>>
						<?php $first = $erfanpro_services[0]; ?>
						<?php if ( $first['image'] ) : ?>
						<img alt="<?php echo esc_attr( $first['title'] ); ?>" class="h-full w-full object-cover" data-svc-pimg src="<?php echo esc_url( $first['image'] ); ?>">
						<?php endif; ?>
						<div class="absolute inset-0 bg-gradient-to-t from-emerald-950/95 via-emerald-950/15 to-transparent"></div>
						<div class="absolute bottom-0 right-0 flex items-center gap-4 p-8">
							<span class="grid h-14 w-14 place-items-center rounded-2xl border border-emerald-300/50 bg-emerald-950/75 backdrop-blur-md" data-svc-picon>
								<?php echo str_replace( 'h-6 w-6', 'h-7 w-7 text-emerald-200', $erfanpro_svc_icons[0] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							</span>
							<div class="flex flex-col">
								<span class="text-2xl font-extrabold text-white" data-svc-ptitle><?php echo esc_html( $first['title'] ); ?></span>
								<span class="max-w-sm text-[13px] font-medium leading-6 text-white/85" data-svc-pdesc><?php echo esc_html( $first['desc'] ); ?></span>
							</div>
						</div>
						<span class="absolute left-7 top-7 rounded-full bg-emerald-950/75 px-4 py-1.5 text-xs font-bold text-emerald-100 backdrop-blur-md" data-svc-pcount><?php echo esc_html( erfanpro_fa_digits( '1' ) . ' / ' . erfanpro_fa_digits( (string) $erfanpro_n ) ); ?></span>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
