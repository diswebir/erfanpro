<?php
/**
 * Phase 5 — the five product tabs (reference layout + data-cls-on/off
 * so main.js switches them):
 * 0 توضیحات | 1 مشخصات فنی | 2 آموزش نصب | 3 کاتالوگ | 4 پرسش‌های متداول.
 *
 * @package Erfanpro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* The assembler sets $erfanpro_product (WC_Product). */
$product    = isset( $args['erfanpro_product'] ) && $args['erfanpro_product'] instanceof WC_Product ? $args['erfanpro_product'] : null;
$product_id = $product ? $product->get_id() : get_queried_object_id();
$title      = $product ? $product->get_name() : get_the_title();

$cls_on  = 'relative flex shrink-0 items-center gap-2 rounded-xl px-4 py-3 text-[13.5px] font-bold transition-all duration-200 sm:px-5 bg-emerald-800 text-white shadow-sm';
$cls_off = 'relative flex shrink-0 items-center gap-2 rounded-xl px-4 py-3 text-[13.5px] font-bold transition-all duration-200 sm:px-5 text-slate-600 hover:bg-emerald-100/60 hover:text-emerald-900';

$tab_icons = array(
	'<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-file-text h-4.5 w-4.5 text-white" aria-hidden="true"><path d="M6 22a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h8a2.4 2.4 0 0 1 1.704.706l3.588 3.588A2.4 2.4 0 0 1 20 8v12a2 2 0 0 1 2-2z"></path><path d="M14 2v5a1 1 0 0 0 1 1h5"></path><path d="M10 9H8"></path><path d="M16 13H8"></path><path d="M16 17H8"></path></svg>',
	'<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-layers lucide-layers-3 h-4.5 w-4.5 text-emerald-700" aria-hidden="true"><path d="M12.83 2.18a2 2 0 0 0-1.66 0L2.6 6.08a1 1 0 0 0 0 1.83l8.58 3.91a2 2 0 0 0 1.66 0l8.58-3.9a1 1 0 0 0 0-1.83z"></path><path d="M2 12a1 1 0 0 0 .58.91l8.6 3.91a2 2 0 0 0 1.65 0l8.58-3.9A1 1 0 0 0 22 12"></path><path d="M2 17a1 1 0 0 0 .58.91l8.6 3.91a2 2 0 0 0 1.65 0l8.58-3.9A1 1 0 0 0 22 17"></path></svg>',
	'<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-wrench h-4.5 w-4.5 text-emerald-700" aria-hidden="true"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.106-3.105c.32-.322.863-.22.983.218a6 6 0 0 1-8.259 7.057l-7.91 7.91a1 1 0 0 1-2.999-3l7.91-7.91a6 6 0 0 1 7.057-8.259c.438.12.54.662.219.984z"></path></svg>',
	'<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-download h-4.5 w-4.5 text-emerald-700" aria-hidden="true"><path d="M12 15V3"></path><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><path d="m7 10 5 5 5-5"></path></svg>',
	'<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-question-mark lucide-help-circle lucide-circle-help h-4.5 w-4.5 text-emerald-700" aria-hidden="true"><circle cx="12" cy="12" r="10"></circle><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path><path d="M12 17h.01"></path></svg>',
);
$tab_labels = array(
	__( 'توضیحات محصول', 'erfanpro' ),
	__( 'مشخصات فنی', 'erfanpro' ),
	__( 'آموزش نصب و استفاده', 'erfanpro' ),
	__( 'کاتالوگ و دانلود', 'erfanpro' ),
	__( 'پرسش‌های متداول', 'erfanpro' ),
);

/* ---- data ---- */
$content     = apply_filters( 'the_content', get_post_field( 'post_content', $product_id ) );
$about       = (string) get_post_meta( $product_id, 'about-productt', true );
$features    = erfanpro_repeater_column( erfanpro_get_repeater( $product_id, 'product-feature' ), 'add-new-feature' );
$usages      = erfanpro_repeater_column( erfanpro_get_repeater( $product_id, 'usage_product' ), 'add-new-usage' );
$before_sale = (string) get_post_meta( $product_id, 'before-sale-c', true );
$catalog     = erfanpro_product_catalog( $product_id );
$catalog_all = erfanpro_option( 'catalog_full_url', '' );
$faqs        = erfanpro_get_repeater( $product_id, 'faq' );
$mfaqs       = erfanpro_get_repeater( $product_id, 'mfaq' );

/* Spec table from the visible WooCommerce attributes. */
$spec_rows = array();
if ( $product ) {
	foreach ( $product->get_attributes() as $attr ) {
		if ( ! $attr->get_visible() ) {
			continue;
		}
		$label = wc_attribute_label( $attr->get_name() );
		if ( $attr->is_taxonomy() ) {
			$values = wc_get_product_terms( $product_id, $attr->get_name(), array( 'fields' => 'names' ) );
			$values = is_array( $values ) ? $values : array();
		} else {
			$values = $attr->get_options();
		}
		if ( empty( $values ) ) {
			continue;
		}
		$spec_rows[] = array( $label, implode( '، ', $values ) );
	}
}

$env_fallback = array( __( 'نمای ساختمان', 'erfanpro' ), __( 'خیابان و میدان', 'erfanpro' ), __( 'تالارها و باغ‌های عروسی', 'erfanpro' ), __( 'مغازه‌ها و رستوران‌ها', 'erfanpro' ) );
$safety_fallback = array(
	__( 'قبل از هر اقدام روی سیستم، برق مسیر را کاملاً قطع کنید.', 'erfanpro' ),
	__( 'سیستم ارت را در سرآهای فلزی حتماً اجرا کنید.', 'erfanpro' ),
	__( 'از منبع تغذیه با توان مناسب و دارای حفاظت اتصال کوتاه استفاده کنید.', 'erfanpro' ),
	__( 'تست در ارتقای بازدید باید توسط تیم مجرب یا تجهیزات ایمنی انجام شود.', 'erfanpro' ),
);
$check_svg = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-check lucide-check-circle-2 mt-1 h-4.5 w-4.5 shrink-0 text-emerald-600" aria-hidden="true"><circle cx="12" cy="12" r="10"></circle><path d="m16 9-5.5 5.5L8 12"></path></svg>';
$file_svg  = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-file-text h-5 w-5" aria-hidden="true"><path d="M6 22a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h8a2.4 2.4 0 0 1 1.704.706l3.588 3.588A2.4 2.4 0 0 1 20 8v12a2 2 0 0 1 2-2z"></path><path d="M14 2v5a1 1 0 0 0 1 1h5"></path><path d="M10 9H8"></path><path d="M16 13H8"></path><path d="M16 17H8"></path></svg>';
$icon_on   = 'flex h-7 w-7 shrink-0 items-center justify-center rounded-lg text-lg font-black transition-transform duration-200 bg-emerald-800 text-white rotate-45';
$icon_off  = 'flex h-7 w-7 shrink-0 items-center justify-center rounded-lg text-lg font-black transition-transform duration-200 bg-emerald-800 text-white';
?>
<?php
/* Related projects: manual project-ids, then latest (same data as the
 * projects section below). */
$related_projects = erfanpro_related_projects( $product_id, 3 );
?>
<section class="mx-auto max-w-7xl px-5 pb-12 lg:px-8 lg:pb-16">
	<div>
		<div class="overflow-hidden rounded-3xl border border-emerald-100 bg-white shadow-sm">
			<div class="border-b border-emerald-100 bg-emerald-50/50 p-2 sm:p-2.5">
				<div class="flex gap-1.5 overflow-x-auto no-scrollbar scroll-smooth">
					<?php foreach ( $tab_labels as $i => $label ) : ?>
					<button type="button" class="<?php echo esc_attr( 0 === $i ? $cls_on : $cls_off ); ?>" data-tab-btn="<?php echo esc_attr( (string) $i ); ?>" data-cls-on="<?php echo esc_attr( $cls_on ); ?>" data-cls-off="<?php echo esc_attr( $cls_off ); ?>"><?php echo $tab_icons[ $i ]; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><span><?php echo esc_html( $label ); ?></span></button>
					<?php endforeach; ?>
				</div>
			</div>

			<!-- 0 — توضیحات -->
			<div class="p-6 lg:p-9" data-tab-panel="0">
				<div style="opacity: 1; transform: none;">
					<div class="grid gap-8 lg:grid-cols-12">
						<div class="space-y-5 lg:col-span-7">
							<div class="space-y-4 text-[15px] leading-9 text-slate-700 lg:text-[15.5px] lg:leading-[2.3]">
								<?php echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								<?php if ( '' !== trim( $about ) ) : ?>
								<div><?php echo wp_kses_post( $about ); ?></div>
								<?php endif; ?>
							</div>
							<div class="rounded-2xl border-r-4 border-emerald-600 bg-emerald-50/80 p-5 pr-6">
								<p class="text-[14px] font-bold leading-8 text-emerald-950"><?php esc_html_e( '«شرکت دانش‌بنیان عرفان صنعت اصفهان با تکیه بر دانش فنی و استفاده از برترین چیپ‌های LED، استانداردهای سخت‌گیرانه مقاومت در برابر شرایط جوی (IP67/IP68) را برای این محصول تضمین می‌کند.»', 'erfanpro' ); ?></p>
							</div>
						</div>
						<div class="space-y-5 lg:col-span-5">
							<?php if ( ! empty( $features ) ) : ?>
							<div class="rounded-2xl border border-emerald-100 bg-emerald-50/40 p-6 shadow-xs">
								<h4 class="mb-4 flex items-center gap-2 text-base font-extrabold text-slate-900"><?php echo $check_svg; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php esc_html_e( 'ویژگی‌های برجسته محصول', 'erfanpro' ); ?></h4>
								<ul class="space-y-3">
									<?php foreach ( $features as $f ) : ?>
									<li class="flex items-start gap-2.5 text-[13.5px] font-semibold leading-7 text-slate-800"><?php echo $check_svg; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><span><?php echo esc_html( $f ); ?></span></li>
									<?php endforeach; ?>
								</ul>
							</div>
							<?php endif; ?>
							<div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-xs">
								<h5 class="mb-3 text-[13px] font-bold text-slate-700"><?php esc_html_e( 'محیط‌های ایده‌آل برای نصب:', 'erfanpro' ); ?></h5>
								<div class="flex flex-wrap gap-2">
									<?php foreach ( $env_fallback as $env ) : ?>
									<span class="rounded-lg bg-emerald-50 px-3 py-1.5 text-[12px] font-bold text-emerald-800"><?php echo esc_html( $env ); ?></span>
									<?php endforeach; ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- 1 — مشخصات فنی -->
			<div class="p-6 lg:p-9" data-tab-panel="1" style="display: none;">
				<div style="opacity: 1; transform: none;">
					<div class="grid gap-6 lg:grid-cols-12">
						<div class="order-1 overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-xs lg:order-1 lg:col-span-7">
							<?php if ( ! empty( $spec_rows ) ) : ?>
							<div class="divide-y divide-slate-100">
								<?php foreach ( $spec_rows as $i => $row ) : ?>
								<div class="flex items-center justify-between gap-4 px-6 py-4 text-[14px] <?php echo 0 === $i % 2 ? 'bg-white' : 'bg-slate-50/60'; ?>">
									<span class="font-extrabold text-slate-800"><?php echo esc_html( $row[0] ); ?></span>
									<span class="text-left font-semibold text-slate-600"><?php echo esc_html( $row[1] ); ?></span>
								</div>
								<?php endforeach; ?>
							</div>
							<?php endif; ?>
						</div>
						<div class="order-2 flex flex-col gap-5 lg:order-2 lg:col-span-5">
							<?php if ( ! empty( $usages ) ) : ?>
							<div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-xs">
								<h4 class="mb-4 text-center text-[15px] font-extrabold text-slate-900"><?php esc_html_e( 'کاربردهای پیشنهادی', 'erfanpro' ); ?></h4>
								<div class="flex flex-wrap items-center justify-center gap-2">
									<?php foreach ( $usages as $u ) : ?>
									<span class="rounded-full border border-emerald-200 bg-emerald-50/60 px-4 py-2 text-[12.5px] font-bold text-emerald-800 shadow-xs"><?php echo esc_html( $u ); ?></span>
									<?php endforeach; ?>
								</div>
							</div>
							<?php endif; ?>
							<div class="rounded-2xl border border-emerald-100 bg-emerald-50/50 p-6 shadow-xs">
								<h4 class="mb-2 text-[15px] font-extrabold text-slate-900"><?php esc_html_e( 'تولید سفارشی', 'erfanpro' ); ?></h4>
								<p class="text-[13.5px] leading-7 text-slate-600"><?php esc_html_e( 'امکان تولید در رنگ، طول و تراکم اختصاصی مطابق نیاز پروژه وجود دارد.', 'erfanpro' ); ?></p>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- 2 — آموزش نصب -->
			<div class="p-6 lg:p-9" data-tab-panel="2" style="display: none;">
				<div style="opacity: 1; transform: none;">
					<div class="grid gap-6 lg:grid-cols-12">
						<div class="space-y-3.5 lg:col-span-7">
							<h4 class="mb-2 text-base font-extrabold text-slate-900"><?php esc_html_e( 'مراحل نصب المان‌های روشنایی شهری', 'erfanpro' ); ?></h4>
							<?php foreach ( $usages as $i => $u ) : ?>
							<div class="flex items-center gap-4 rounded-2xl border border-slate-200/80 bg-slate-50/70 p-4 sm:p-5">
								<span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-emerald-800 text-[13px] font-black text-white shadow-xs"><?php echo esc_html( erfanpro_fa_digits( $i + 1 ) ); ?></span>
								<p class="text-[14px] font-medium leading-7 text-slate-700"><?php echo esc_html( $u ); ?></p>
							</div>
							<?php endforeach; ?>
						</div>
						<div class="lg:col-span-5">
							<div class="rounded-2xl border border-amber-200/70 bg-amber-50/60 p-6 shadow-xs">
								<h4 class="mb-4 text-[15px] font-extrabold text-amber-900"><?php esc_html_e( 'نکات ایمنی مهم', 'erfanpro' ); ?></h4>
								<?php if ( '' !== trim( $before_sale ) ) : ?>
								<p class="text-[13.5px] font-medium leading-8 text-amber-900"><?php echo esc_html( $before_sale ); ?></p>
								<?php else : ?>
								<ul class="space-y-2.5">
									<?php foreach ( $safety_fallback as $s ) : ?>
									<li class="flex items-start gap-2.5 text-[13px] leading-6 text-amber-900"><span class="mt-2 h-1.5 w-1.5 shrink-0 rounded-full bg-amber-500"></span><span><?php echo esc_html( $s ); ?></span></li>
									<?php endforeach; ?>
								</ul>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- 3 — کاتالوگ -->
			<div class="p-6 lg:p-9" data-tab-panel="3" style="display: none;">
				<div style="opacity: 1; transform: none;">
					<div class="space-y-3">
						<?php if ( $catalog ) : ?>
						<a href="<?php echo esc_url( $catalog['url'] ); ?>" download class="group flex items-center justify-between gap-4 rounded-2xl border border-slate-200/80 bg-slate-50/60 p-5 transition-all duration-200 hover:border-emerald-300 hover:bg-white hover:shadow-sm">
							<div class="flex items-center gap-3.5">
								<span class="grid h-12 w-12 shrink-0 place-items-center rounded-xl bg-emerald-50 text-emerald-800"><?php echo $file_svg; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
								<div class="min-w-0">
									<span class="block text-[14px] font-extrabold text-slate-900 transition-colors group-hover:text-emerald-700"><?php echo esc_html( sprintf( __( 'کاتالوگ فنی %s', 'erfanpro' ), $title ) ); ?></span>
									<span class="text-[12px] text-slate-500"><?php esc_html_e( 'مشخصات و رنگ‌بندی', 'erfanpro' ); ?> — <?php echo esc_html( $catalog['ext'] ); ?></span>
								</div>
							</div>
							<span class="rounded-lg bg-emerald-100/70 px-3 py-1.5 text-[11px] font-black text-emerald-800"><?php echo esc_html( $catalog['ext'] ); ?></span>
						</a>
						<?php endif; ?>
					</div>
					<div class="mt-4 rounded-2xl border border-slate-200/80 bg-slate-50/80 p-6 shadow-xs">
						<div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
							<div>
								<h4 class="text-[15px] font-extrabold text-slate-900"><?php esc_html_e( 'دریافت کاتالوگ جامع شرکت', 'erfanpro' ); ?></h4>
								<p class="mt-1 text-[13px] text-slate-500"><?php esc_html_e( 'شامل تمام محصولات، ابعاد و مشخصات فنی.', 'erfanpro' ); ?></p>
							</div>
							<?php
							$full_url  = $catalog ? $catalog['url'] : '';
							if ( '' === $full_url && '' !== $catalog_all ) {
								$full_url = $catalog_all;
							}
							?>
							<?php if ( '' !== $full_url ) : ?>
							<a href="<?php echo esc_url( $full_url ); ?>" download class="inline-flex shrink-0 items-center justify-center rounded-xl bg-emerald-800 px-7 py-3 text-[14px] font-black text-white shadow-xs transition-all hover:bg-emerald-900"><?php esc_html_e( 'دریافت کاتالوگ', 'erfanpro' ); ?></a>
							<?php else : ?>
							<a href="tel:<?php echo esc_attr( erfanpro_phone_tel() ); ?>" class="inline-flex shrink-0 items-center justify-center rounded-xl bg-emerald-800 px-7 py-3 text-[14px] font-black text-white shadow-xs transition-all hover:bg-emerald-900"><?php esc_html_e( 'درخواست کاتالوگ', 'erfanpro' ); ?></a>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>

			<!-- 4 — پرسش‌های متداول -->
			<div class="p-6 lg:p-9" data-tab-panel="4" style="display: none;">
				<div style="opacity: 1; transform: none;">
					<div class="space-y-3">
						<?php
						$all_faq = array();
						foreach ( $faqs as $f ) {
							$all_faq[] = $f;
						}
						foreach ( $mfaqs as $f ) {
							$all_faq[] = $f;
						}
						?>
						<?php foreach ( $all_faq as $i => $f ) :
							$q = isset( $f['name_question'] ) ? trim( (string) $f['name_question'] ) : '';
							$a = isset( $f['answer_question'] ) ? trim( (string) $f['answer_question'] ) : '';
							if ( '' === $q ) {
								continue;
							}
							$open = 0 === $i;
						?>
						<div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-slate-50/50 transition-colors hover:border-emerald-200" data-faq-item="<?php echo esc_attr( (string) $i ); ?>">
							<button type="button" class="flex w-full items-center justify-between gap-4 px-6 py-4.5 text-right font-bold text-slate-800 transition-colors" data-faq-btn="">
								<span class="text-[14.5px]"><?php echo esc_html( $q ); ?></span>
								<span class="<?php echo esc_attr( $open ? $icon_on : $icon_off ); ?>" data-icon-on="<?php echo esc_attr( $icon_on ); ?>" data-icon-off="<?php echo esc_attr( $icon_off ); ?>">+</span>
							</button>
							<div class="overflow-hidden"<?php echo $open ? ' style="height: auto; opacity: 1;"' : ' style="display: none;"'; ?> data-faq-answer="">
								<p class="border-t border-slate-200/80 bg-white px-6 py-4.5 text-[14px] leading-8 text-slate-600"><?php echo wp_kses_post( $a ); ?></p>
							</div>
						</div>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php if ( ! empty( $related_projects ) ) : ?>
	<div class="mt-16">
		<div class="mb-8 flex items-center justify-between">
			<div<?php erfanpro_reveal( 0.00, 'inview', null, 'opacity: 1; filter: blur(0px); transform: none;' ); ?>>
				<h3 class="text-2xl font-extrabold text-slate-900"><?php esc_html_e( 'پروژه‌های اجراشده با این محصول', 'erfanpro' ); ?></h3>
				<p class="mt-1 text-[13.5px] text-slate-500"><?php esc_html_e( 'نمونه پروژه‌های واقعی در سراسر کشور که از این تجهیزات استفاده کرده‌اند', 'erfanpro' ); ?></p>
			</div>
			<a class="group hidden items-center gap-2 text-sm font-bold text-emerald-700 hover:text-emerald-800 sm:flex" href="<?php echo esc_url( erfanpro_archive_url( 'projects' ) ); ?>"><?php esc_html_e( 'همه پروژه‌ها', 'erfanpro' ); ?><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-left h-4 w-4 transition-transform group-hover:-translate-x-1" aria-hidden="true"><path d="M19 12H5"></path><path d="m12 19-7-7 7-7"></path></svg></a>
		</div>
		<div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
			<?php foreach ( $related_projects as $i => $pid ) :
				$ptitle = get_the_title( $pid );
				$purl   = get_permalink( $pid );
				$pimg   = get_the_post_thumbnail_url( $pid, 'large' );
				$city   = '';
				$cterms = get_the_terms( $pid, 'city' );
				if ( ! is_wp_error( $cterms ) && ! empty( $cterms ) ) {
					$city = $cterms[0]->name;
				}
				$ptype  = '';
				$ttypes = get_the_terms( $pid, 'project_type' );
				if ( ! is_wp_error( $ttypes ) && ! empty( $ttypes ) ) {
					$ptype = $ttypes[0]->name;
				}
				$year = (string) get_post_meta( $pid, 'finish-pro-time', true );
				if ( '' !== $year && preg_match( '/^(\d{4})/', $year, $m ) ) {
					$year = erfanpro_fa_digits( $m[1] );
				} else {
					$year = '';
				}
			?>
			<div<?php erfanpro_reveal( $i * 0.07, 'inview', null, 'opacity: 1; filter: blur(0px); transform: none;' ); ?>>
				<a class="card-glow group relative block h-64 overflow-hidden rounded-[1.5rem] border border-emerald-100" href="<?php echo esc_url( $purl ); ?>">
					<?php if ( $pimg ) : ?>
					<img alt="<?php echo esc_attr( $ptitle ); ?>" loading="lazy" class="h-full w-full object-cover transition-transform duration-1000 group-hover:scale-108" src="<?php echo esc_url( $pimg ); ?>">
					<?php endif; ?>
					<div class="image-overlay absolute inset-0"></div>
					<?php if ( '' !== $ptype ) : ?>
					<span class="absolute right-4 top-4 rounded-full bg-emerald-950/75 px-3.5 py-1.5 text-[11px] font-bold text-emerald-100 backdrop-blur-md"><?php echo esc_html( $ptype ); ?></span>
					<?php endif; ?>
					<div class="absolute inset-x-0 bottom-0 p-5">
						<?php if ( '' !== $city || '' !== $year ) : ?>
						<span class="text-[11.5px] font-bold text-emerald-200">📍 <?php echo esc_html( trim( $city . ( '' !== $city && '' !== $year ? ' — ' : '' ) . ( '' !== $year ? __( 'سال ', 'erfanpro' ) . $year : '' ) ) ); ?></span>
						<?php endif; ?>
						<h4 class="mt-1.5 text-[15.5px] font-extrabold leading-7 text-white [text-shadow:0_2px_10px_rgba(0,0,0,.8)]"><?php echo esc_html( $ptitle ); ?></h4>
					</div>
				</a>
			</div>
			<?php endforeach; ?>
		</div>
	</div>
	<?php endif; ?>
</section>
