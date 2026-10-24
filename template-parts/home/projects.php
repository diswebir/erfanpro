<?php
/**
 * Home — featured projects (phase 3).
 *
 * Three latest `projects` CPT items in the reference layout (one large
 * card + two stacked cards). Title, `project_type` term and excerpt
 * come from real data; the reference cards are the design fallback.
 *
 * @package Erfanpro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$erfanpro_projects = array();
if ( post_type_exists( 'projects' ) ) {
	$q = new WP_Query(
		array(
			'post_type'      => 'projects',
			'post_status'    => 'publish',
			'posts_per_page' => 3,
			'orderby'        => 'date',
			'order'          => 'DESC',
			'no_found_rows'  => true,
		)
	);
	if ( $q->have_posts() ) {
		while ( $q->have_posts() ) {
			$q->the_post();
			$type_name = '';
			$types     = get_the_terms( get_the_ID(), 'project_type' );
			if ( ! is_wp_error( $types ) && ! empty( $types ) ) {
				$type_name = $types[0]->name;
			}
			$img = get_the_post_thumbnail_url( get_the_ID(), 'large' );
			$erfanpro_projects[] = array(
				'title' => get_the_title(),
				'type'  => $type_name,
				'desc'  => has_excerpt() ? get_the_excerpt() : wp_trim_words( wp_strip_all_tags( get_the_content() ), 22 ),
				'image' => $img ? $img : '',
				'url'   => get_permalink(),
			);
		}
		wp_reset_postdata();
	}
}

/* Reference design fallback (3 static cards). */
if ( count( $erfanpro_projects ) < 3 ) {
	$erfanpro_projects = array(
		array(
			'title' => 'تونل نوری',
			'type'  => 'کمانی، نیم‌آرک، ۹۰ درجه، مربعی و قلبی',
			'desc'  => 'مسیرهای عبوری با جلوه‌ای جادویی؛ از ورودی بوستان‌ها تا جشنواره‌های شهری',
			'image' => ERFANPRO_URI . '/assets/img/light-tunnel.jpg',
			'url'   => erfanpro_archive_url( 'projects' ),
		),
		array(
			'title' => 'گوی نورانی',
			'type'  => 'المان حجمی نورافکن',
			'desc'  => 'کره‌های درخشان نقطه‌کانونی میادین و فضاهای باز شهری',
			'image' => ERFANPRO_URI . '/assets/img/light-sphere.jpg',
			'url'   => erfanpro_archive_url( 'projects' ),
		),
		array(
			'title' => 'المان نوری',
			'type'  => 'سازه‌های نوری اختصاصی',
			'desc'  => 'طراحی و ساخت المان‌های نوری منحصربه‌فرد با هویت بصری ماندگار',
			'image' => ERFANPRO_URI . '/assets/img/light-element.jpg',
			'url'   => erfanpro_archive_url( 'projects' ),
		),
	);
}

$erfanpro_sec = erfanpro_home_section( 'projects' );
?>
<section id="projects" class="relative z-10 mx-auto max-w-7xl scroll-mt-20 px-5 py-24 lg:px-8 lg:py-32">
	<div class="flex max-w-3xl flex-col gap-4 items-center text-center mx-auto">
		<div<?php erfanpro_reveal( 0.00 ); ?>>
			<span class="inline-flex items-center gap-2 rounded-full border border-emerald-500/25 bg-emerald-50 px-4 py-1.5 text-[13px] font-bold tracking-wide text-emerald-800 shadow-xs">
				<span class="relative flex h-2 w-2">
					<span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-500 opacity-60"></span>
					<span class="relative inline-flex h-2 w-2 rounded-full bg-emerald-600"></span>
				</span><?php esc_html_e( 'نمونه‌کارهای برتر', 'erfanpro' ); ?>
			</span>
		</div>
		<div<?php erfanpro_reveal( 0.07 ); ?>>
			<?php if ( '' !== $erfanpro_sec['title'] ) : ?>
			<h2 class="text-3xl font-extrabold leading-[1.35] text-slate-900 sm:text-4xl lg:text-[2.6rem] lg:leading-[1.3]"><?php echo esc_html( $erfanpro_sec['title'] ); ?></h2>
			<?php else : ?>
			<h2 class="text-3xl font-extrabold leading-[1.35] text-slate-900 sm:text-4xl lg:text-[2.6rem] lg:leading-[1.3]"><?php esc_html_e( 'پروژه‌های', 'erfanpro' ); ?> <span class="bg-gradient-to-l from-emerald-800 via-emerald-600 to-teal-700 bg-clip-text text-transparent"><?php esc_html_e( 'نورپردازی شهری', 'erfanpro' ); ?></span></h2>
			<?php endif; ?>
		</div>
		<div<?php erfanpro_reveal( 0.14 ); ?>>
			<p class="text-base leading-8 text-slate-600 lg:text-[17px] lg:leading-9"><?php echo '' !== $erfanpro_sec['subtitle'] ? esc_html( $erfanpro_sec['subtitle'] ) : esc_html__( 'نمونه پروژه‌های نورپردازی حرفه‌ای شهری؛ هر پروژه، روایتی از هنر، مهندسی و نور است که هویت بصری شهر را برای همیشه دگرگون می‌کند.', 'erfanpro' ); ?></p>
		</div>
	</div>
	<div class="mt-14 grid gap-6 lg:grid-cols-5">
		<div class="lg:col-span-3"<?php erfanpro_reveal( 0.00 ); ?>>
			<?php
			$p = $erfanpro_projects[0];
			?>
			<a class="card-glow group relative block overflow-hidden rounded-[1.75rem] border border-emerald-100 bg-slate-900 shadow-sm min-h-[420px] lg:min-h-[560px]" href="<?php echo esc_url( $p['url'] ); ?>">
				<?php if ( $p['image'] ) : ?>
				<img alt="<?php echo esc_attr( $p['title'] ); ?>" loading="lazy" class="absolute inset-0 h-full w-full object-cover transition-transform duration-[1.4s] ease-[cubic-bezier(0.22,1,0.36,1)] group-hover:scale-110" src="<?php echo esc_url( $p['image'] ); ?>">
				<?php endif; ?>
				<div class="image-overlay absolute inset-0"></div>
				<div class="absolute inset-0 bg-emerald-700/0 transition-colors duration-700 group-hover:bg-emerald-700/10"></div>
				<span class="absolute left-6 top-5 font-black text-4xl text-white/25 transition-colors duration-500 group-hover:text-emerald-200/80"><?php echo esc_html( erfanpro_fa_digits( '01' ) ); ?></span>
				<span class="absolute right-6 top-5 grid h-11 w-11 place-items-center rounded-full border border-white/30 bg-emerald-950/70 backdrop-blur-md transition-all duration-500 group-hover:rotate-45 group-hover:border-emerald-400 group-hover:bg-emerald-500"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-up-left h-5 w-5 text-white" aria-hidden="true"><path d="M7 17V7h10"></path><path d="M17 17 7 7"></path></svg></span>
				<div class="absolute inset-x-0 bottom-0 flex flex-col gap-2 p-7 lg:p-8">
					<div class="flex items-center gap-3">
						<h3 class="font-extrabold text-3xl lg:text-4xl"><span class="text-white [text-shadow:0_2px_14px_rgba(0,0,0,.8)]"><?php echo esc_html( $p['title'] ); ?></span></h3>
						<span class="hidden h-px flex-1 bg-gradient-to-l from-emerald-300/70 to-transparent sm:block"></span>
					</div>
					<?php if ( '' !== $p['type'] ) : ?>
					<p class="text-[13.5px] font-semibold text-emerald-200"><?php echo esc_html( $p['type'] ); ?></p>
					<?php endif; ?>
					<?php if ( '' !== $p['desc'] ) : ?>
					<p class="max-w-md text-[13.5px] font-medium leading-7 text-white/90"><?php echo esc_html( $p['desc'] ); ?></p>
					<?php endif; ?>
				</div>
			</a>
		</div>
		<div class="grid gap-6 lg:col-span-2">
			<?php foreach ( array( $erfanpro_projects[1], $erfanpro_projects[2] ) as $i => $p ) : ?>
			<div<?php erfanpro_reveal( 0.07 * $i ); ?>>
				<a class="card-glow group relative block overflow-hidden rounded-[1.75rem] border border-emerald-100 bg-slate-900 shadow-sm min-h-[270px]" href="<?php echo esc_url( $p['url'] ); ?>">
					<?php if ( $p['image'] ) : ?>
					<img alt="<?php echo esc_attr( $p['title'] ); ?>" loading="lazy" class="absolute inset-0 h-full w-full object-cover transition-transform duration-[1.4s] ease-[cubic-bezier(0.22,1,0.36,1)] group-hover:scale-110" src="<?php echo esc_url( $p['image'] ); ?>">
					<?php endif; ?>
					<div class="image-overlay absolute inset-0"></div>
					<div class="absolute inset-0 bg-emerald-700/0 transition-colors duration-700 group-hover:bg-emerald-700/10"></div>
					<span class="absolute left-6 top-5 font-black text-4xl text-white/25 transition-colors duration-500 group-hover:text-emerald-200/80"><?php echo esc_html( erfanpro_fa_digits( $i + 2 < 10 ? '0' . ( $i + 2 ) : (string) ( $i + 2 ) ) ); ?></span>
					<span class="absolute right-6 top-5 grid h-11 w-11 place-items-center rounded-full border border-white/30 bg-emerald-950/70 backdrop-blur-md transition-all duration-500 group-hover:rotate-45 group-hover:border-emerald-400 group-hover:bg-emerald-500"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-up-left h-5 w-5 text-white" aria-hidden="true"><path d="M7 17V7h10"></path><path d="M17 17 7 7"></path></svg></span>
					<div class="absolute inset-x-0 bottom-0 flex flex-col gap-2 p-7 lg:p-8">
						<div class="flex items-center gap-3">
							<h3 class="font-extrabold text-2xl"><span class="text-white [text-shadow:0_2px_14px_rgba(0,0,0,.8)]"><?php echo esc_html( $p['title'] ); ?></span></h3>
							<span class="hidden h-px flex-1 bg-gradient-to-l from-emerald-300/70 to-transparent sm:block"></span>
						</div>
						<?php if ( '' !== $p['type'] ) : ?>
						<p class="text-[13.5px] font-semibold text-emerald-200"><?php echo esc_html( $p['type'] ); ?></p>
						<?php endif; ?>
						<?php if ( '' !== $p['desc'] ) : ?>
						<p class="text-[13.5px] font-medium leading-7 text-white/90"><?php echo esc_html( $p['desc'] ); ?></p>
						<?php endif; ?>
					</div>
				</a>
			</div>
			<?php endforeach; ?>
		</div>
	</div>
	<div class="mt-10 flex justify-center"<?php erfanpro_reveal( 0.00 ); ?>>
		<a class="group inline-flex items-center gap-3 rounded-full border border-emerald-200 bg-white px-7 py-3.5 text-sm font-bold text-slate-800 shadow-sm transition-all duration-400 hover:border-emerald-500 hover:text-emerald-800" href="<?php echo esc_url( '' !== $erfanpro_sec['cta_url'] ? $erfanpro_sec['cta_url'] : erfanpro_archive_url( 'projects' ) ); ?>"><?php echo '' !== $erfanpro_sec['cta_text'] ? esc_html( $erfanpro_sec['cta_text'] ) : esc_html__( 'مشاهده آرشیو کامل پروژه‌ها', 'erfanpro' ); ?><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-up-left h-4.5 w-4.5 transition-transform duration-400 group-hover:-translate-y-1 group-hover:-translate-x-1" aria-hidden="true"><path d="M7 17V7h10"></path><path d="M17 17 7 7"></path></svg></a>
	</div>
</section>
