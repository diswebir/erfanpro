<?php
/**
 * Search results — phase 9 (project decisions 20 + 30).
 *
 * Site-wide search in the reference visual style: dark emerald hero
 * with the query, type-filter chips (all / products / projects /
 * services / posts / pages — the same scopes the header search
 * promises), reference-style result cards, and pagination that
 * preserves the query and the type filter. The header search bar
 * stays a standard GET form (?s=); this template renders its results.
 *
 * @package Erfanpro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$erfanpro_query = get_search_query();
$erfanpro_types = array(
	'all'      => __( 'همه', 'erfanpro' ),
	'product'  => __( 'محصولات', 'erfanpro' ),
	'projects' => __( 'پروژه‌ها', 'erfanpro' ),
	'services' => __( 'خدمات', 'erfanpro' ),
	'post'     => __( 'مقالات', 'erfanpro' ),
	'page'     => __( 'برگه‌ها', 'erfanpro' ),
);
$erfanpro_type  = isset( $_GET['type'] ) ? sanitize_key( wp_unslash( $_GET['type'] ) ) : 'all'; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only filter.
if ( ! isset( $erfanpro_types[ $erfanpro_type ] ) ) {
	$erfanpro_type = 'all';
}

$erfanpro_post_types = array();
if ( 'all' === $erfanpro_type ) {
	foreach ( array( 'product', 'projects', 'services', 'post', 'page' ) as $erfanpro_pt ) {
		if ( post_type_exists( $erfanpro_pt ) ) {
			$erfanpro_post_types[] = $erfanpro_pt;
		}
	}
} else {
	$erfanpro_post_types = array( $erfanpro_type );
}

$erfanpro_results = new WP_Query(
	array(
		's'              => $erfanpro_query,
		'post_type'      => $erfanpro_post_types,
		'post_status'    => 'publish',
		'posts_per_page' => 10,
		'paged'          => max( 1, get_query_var( 'paged', 1 ) ),
	)
);

$erfanpro_type_labels = array(
	'product'  => __( 'محصول', 'erfanpro' ),
	'projects' => __( 'پروژه', 'erfanpro' ),
	'services' => __( 'خدمت', 'erfanpro' ),
	'post'     => __( 'مقاله', 'erfanpro' ),
	'page'     => __( 'برگه', 'erfanpro' ),
);

get_header();
?>
<main class="min-h-screen bg-[#f8faf9]">
	<section class="relative bg-emerald-950 pt-28 lg:pt-36">
		<div aria-hidden="true" class="pointer-events-none absolute inset-0 overflow-hidden"><div class="absolute inset-0 bg-[radial-gradient(1100px_520px_at_50%_-10%,rgba(16,185,129,0.22),transparent_60%)]"></div><div class="absolute inset-0 opacity-70" style="background-image: radial-gradient(rgba(255, 255, 255, 0.5) 1px, transparent 1.4px); background-size: 90px 90px; mask-image: radial-gradient(1000px 560px at 50% 20%, black, transparent 78%);"></div></div>
		<div class="relative z-10 mx-auto max-w-7xl px-5 pb-16 pt-10 lg:px-8 lg:pb-20 lg:pt-14">
			<nav aria-label="<?php esc_attr_e( 'مسیر صفحه', 'erfanpro' ); ?>" class="inline-flex max-w-full flex-wrap items-center gap-x-1.5 gap-y-1.5 rounded-2xl border border-white/15 bg-white/[0.07] px-4 py-2.5 text-[12.5px] font-semibold text-white/70 backdrop-blur">
				<a class="rounded-lg px-1.5 py-0.5 transition-colors hover:bg-white/10 hover:text-emerald-200" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'صفحه اصلی', 'erfanpro' ); ?></a>
				<span class="text-white/30">‹</span>
				<span class="rounded-lg bg-emerald-400/15 px-2 py-0.5 font-extrabold text-emerald-200"><?php esc_html_e( 'جستجو', 'erfanpro' ); ?></span>
			</nav>
			<h1 class="mt-8 text-[2rem] font-black leading-[1.3] text-white sm:text-[2.6rem] lg:text-[3.2rem]">
				<?php
				printf(
					/* translators: %s: search query. */
					esc_html__( 'نتایج جستجو برای «%s»', 'erfanpro' ),
					esc_html( $erfanpro_query )
				);
				?>
			</h1>
			<p class="mt-4 text-[15px] font-medium leading-9 text-white/70">
				<?php
				printf(
					/* translators: %d: number of results. */
					esc_html__( '%d نتیجه یافت شد', 'erfanpro' ),
					(int) $erfanpro_results->found_posts
				);
				?>
			</p>
			<div class="mt-8 flex flex-wrap gap-2.5">
				<?php foreach ( $erfanpro_types as $erfanpro_key => $erfanpro_label ) : ?>
					<a href="<?php echo esc_url( add_query_arg( array( 's' => $erfanpro_query, 'type' => $erfanpro_key ), home_url( '/' ) ) ); ?>" class="rounded-full px-5 py-2.5 text-[13.5px] font-bold transition-colors <?php echo $erfanpro_key === $erfanpro_type ? 'bg-amber-300 text-emerald-950' : 'border border-white/20 bg-white/[0.07] text-white hover:bg-white/[0.14]'; ?>"><?php echo esc_html( $erfanpro_label ); ?></a>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<section class="mx-auto max-w-7xl px-5 py-16 lg:px-8 lg:py-20">
		<?php if ( $erfanpro_results->have_posts() ) : ?>
			<div class="flex flex-col gap-5">
				<?php
				while ( $erfanpro_results->have_posts() ) {
					$erfanpro_results->the_post();
					$erfanpro_pt = get_post_type();
					?>
					<article class="card-light flex flex-col gap-5 rounded-3xl p-6 sm:flex-row sm:items-start sm:gap-6">
						<?php if ( has_post_thumbnail() ) : ?>
							<a href="<?php the_permalink(); ?>" class="block shrink-0 overflow-hidden rounded-2xl">
								<?php the_post_thumbnail( 'medium', array( 'class' => 'h-28 w-28 object-cover sm:h-32 sm:w-32' ) ); ?>
							</a>
						<?php endif; ?>
						<div class="min-w-0 flex-1">
							<span class="inline-flex items-center rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1 text-[12px] font-bold text-emerald-800"><?php echo esc_html( $erfanpro_type_labels[ $erfanpro_pt ] ?? $erfanpro_pt ); ?></span>
							<h2 class="mt-3 text-lg font-extrabold leading-8 text-slate-900 lg:text-xl"><a class="transition-colors hover:text-emerald-700" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
							<?php if ( has_excerpt() ) : ?>
								<p class="mt-2 text-[14px] leading-8 text-slate-600"><?php echo esc_html( get_the_excerpt() ); ?></p>
							<?php endif; ?>
							<?php if ( 'product' === $erfanpro_pt && function_exists( 'wc_get_product' ) ) : ?>
								<?php
								$erfanpro_product = wc_get_product( get_the_ID() );
								if ( $erfanpro_product ) :
									?>
									<p class="mt-3 text-[14px] font-extrabold text-emerald-700">
										<?php echo '' !== $erfanpro_product->get_price() ? wp_kses_post( $erfanpro_product->get_price_html() ) : esc_html__( 'استعلام قیمت', 'erfanpro' ); ?>
									</p>
								<?php endif; ?>
							<?php endif; ?>
							<a class="mt-3 inline-flex items-center gap-1.5 text-[13.5px] font-bold text-emerald-700 hover:text-emerald-800" href="<?php the_permalink(); ?>"><?php esc_html_e( 'مشاهده', 'erfanpro' ); ?><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-up-left h-4 w-4" aria-hidden="true"><path d="M7 17V7h10"></path><path d="M17 17 7 7"></path></svg></a>
						</div>
					</article>
					<?php
				}
				wp_reset_postdata();
				?>
			</div>
			<nav class="mt-12 flex justify-center" aria-label="<?php esc_attr_e( 'صفحه‌بندی نتایج', 'erfanpro' ); ?>">
				<?php
				echo paginate_links( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- paginate_links output.
					array(
						'total'   => $erfanpro_results->max_num_pages,
						'current' => max( 1, get_query_var( 'paged', 1 ) ),
						'add_args' => array(
							's'    => $erfanpro_query,
							'type' => $erfanpro_type,
						),
					)
				);
				?>
			</nav>
		<?php else : ?>
			<div class="card-light mx-auto max-w-2xl rounded-[2rem] p-8 text-center lg:p-10">
				<p class="text-5xl font-black text-emerald-700">؟</p>
				<h2 class="mt-4 text-xl font-extrabold text-slate-900"><?php esc_html_e( 'برای جستجوی شما نتیجه‌ای پیدا نشد', 'erfanpro' ); ?></h2>
				<p class="mt-3 text-[14px] leading-8 text-slate-600"><?php esc_html_e( 'املای عبارت را بررسی کنید یا با کلمات کوتاه‌تر و کلی‌تر دوباره جستجو کنید.', 'erfanpro' ); ?></p>
				<div class="mx-auto mt-6 max-w-md">
					<?php get_search_form(); ?>
				</div>
				<div class="mt-8 flex flex-wrap justify-center gap-3">
					<a class="rounded-full bg-emerald-600 px-6 py-3 text-[13.5px] font-bold text-white transition-colors hover:bg-emerald-700" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'صفحه اصلی', 'erfanpro' ); ?></a>
					<a class="rounded-full border border-emerald-200 bg-emerald-50 px-6 py-3 text-[13.5px] font-bold text-emerald-800 transition-colors hover:bg-emerald-100" href="<?php echo esc_url( erfanpro_archive_url( 'product' ) ); ?>"><?php esc_html_e( 'فروشگاه', 'erfanpro' ); ?></a>
					<a class="rounded-full border border-emerald-200 bg-emerald-50 px-6 py-3 text-[13.5px] font-bold text-emerald-800 transition-colors hover:bg-emerald-100" href="<?php echo esc_url( erfanpro_contact_url() ); ?>"><?php esc_html_e( 'تماس با ما', 'erfanpro' ); ?></a>
				</div>
			</div>
		<?php endif; ?>
	</section>
</main>
<?php
get_footer();
