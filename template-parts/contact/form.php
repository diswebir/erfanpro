<?php
/**
 * Contact — live consultation form (phase 9).
 *
 * Reference field layout (contact.html): name, mobile, organization,
 * project description. This form posts to the phase-1 leads
 * infrastructure: `erfanpro_save_lead()` in inc/leads.php with the
 * unique form_id `contact-main`; the `erfanpro_lead_saved` action fires
 * there for the future per-lead SMS hook, and the
 * `erfanpro_captcha_html` filter renders here when a captcha is added
 * (decision 39: architecture ready, not implemented).
 *
 * AJAX (assets/js/contact.js) posts to admin-ajax.php; without JS the
 * same fields post to admin-post.php and redirect back with a status
 * flag, handled by the notice below.
 *
 * @package Erfanpro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$erfanpro_contact_status = isset( $_GET['erfanpro_contact'] ) ? sanitize_key( wp_unslash( $_GET['erfanpro_contact'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only status flag.
?>
<?php if ( 'sent' === $erfanpro_contact_status ) : ?>
	<div data-erfanpro-contact-status class="mb-5 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-[14px] font-bold text-emerald-800" role="status"><?php esc_html_e( 'درخواست شما با موفقیت ثبت شد؛ کارشناسان ما به‌زودی با شما تماس می‌گیرند.', 'erfanpro' ); ?></div>
<?php elseif ( 'error' === $erfanpro_contact_status ) : ?>
	<div data-erfanpro-contact-status class="mb-5 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-[14px] font-bold text-red-800" role="alert"><?php esc_html_e( 'ثبت درخواست ناموفق بود؛ لطفاً نام و شماره موبایل معتبر (۰۹…) را بررسی و دوباره تلاش کنید.', 'erfanpro' ); ?></div>
<?php else : ?>
	<div data-erfanpro-contact-status class="mb-5 hidden rounded-2xl border px-5 py-4 text-[14px] font-bold" role="status"></div>
<?php endif; ?>
<form data-erfanpro-contact-form class="mt-8 flex flex-col gap-5" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
	<input type="hidden" name="action" value="erfanpro_contact_form" />
	<?php wp_nonce_field( 'erfanpro-contact-form' ); ?>
	<input type="hidden" name="form_id" value="contact-main" />
	<label class="absolute left-[-9999px] top-auto h-px w-px overflow-hidden" aria-hidden="true"><?php esc_html_e( 'این فیلد را خالی بگذارید', 'erfanpro' ); ?><input type="text" name="erfanpro_website" value="" tabindex="-1" autocomplete="off" /></label>
	<div class="grid gap-5 sm:grid-cols-2">
		<label class="flex flex-col gap-2">
			<span class="text-[13px] font-bold text-slate-700"><?php esc_html_e( 'نام و نام خانوادگی *', 'erfanpro' ); ?></span>
			<div class="relative">
				<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user absolute right-4 top-1/2 h-4.5 w-4.5 -translate-y-1/2 text-slate-400" aria-hidden="true"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
				<input required name="name" type="text" placeholder="<?php esc_attr_e( 'نام شما', 'erfanpro' ); ?>" class="w-full rounded-xl border border-slate-200 bg-slate-50 py-3.5 pr-11 pl-4 text-[14px] text-slate-800 outline-none transition-all placeholder:text-slate-400 focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10" />
			</div>
		</label>
		<label class="flex flex-col gap-2">
			<span class="text-[13px] font-bold text-slate-700"><?php esc_html_e( 'شماره تماس *', 'erfanpro' ); ?></span>
			<div class="relative">
				<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-phone-call absolute right-4 top-1/2 h-4.5 w-4.5 -translate-y-1/2 text-slate-400" aria-hidden="true"><path d="M13 2a9 9 0 0 1 9 9"></path><path d="M13 6a5 5 0 0 1 5 5"></path><path d="M13.832 16.568a1 1 0 0 0 1.213-.303l.355-.465A2 2 0 0 1 17 15h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2A18 18 0 0 1 2 4a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v3a2 2 0 0 1-.8 1.6l-.468.351a1 1 0 0 0-.292 1.233 14 14 0 0 0 6.392 6.384"></path></svg>
				<input required name="mobile" type="tel" dir="rtl" placeholder="<?php esc_attr_e( '۰۹۱۲ …', 'erfanpro' ); ?>" class="w-full rounded-xl border border-slate-200 bg-slate-50 py-3.5 pr-11 pl-4 text-[14px] text-slate-800 outline-none transition-all placeholder:text-slate-400 focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10" />
			</div>
		</label>
	</div>
	<label class="flex flex-col gap-2">
		<span class="text-[13px] font-bold text-slate-700"><?php esc_html_e( 'سازمان / شهرداری', 'erfanpro' ); ?></span>
		<input name="subject" type="text" placeholder="<?php esc_attr_e( 'نام سازمان یا شرکت (اختیاری)', 'erfanpro' ); ?>" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3.5 text-[14px] text-slate-800 outline-none transition-all placeholder:text-slate-400 focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10" />
	</label>
	<label class="flex flex-col gap-2">
		<span class="text-[13px] font-bold text-slate-700"><?php esc_html_e( 'توضیحات پروژه *', 'erfanpro' ); ?></span>
		<textarea required name="message" rows="4" placeholder="<?php esc_attr_e( 'نوع پروژه (میدان، خیابان، تونل نوری و…)، محل اجرا و توضیحات لازم', 'erfanpro' ); ?>" class="w-full resize-none rounded-xl border border-slate-200 bg-slate-50 px-4 py-3.5 text-[14px] leading-7 text-slate-800 outline-none transition-all placeholder:text-slate-400 focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10"></textarea>
	</label>
	<?php
	// Captcha injection point (decision 39): empty until implemented.
	echo apply_filters( 'erfanpro_captcha_html', '', 'contact-main' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- filtered captcha HTML.
	?>
	<button type="submit" data-erfanpro-contact-submit class="group flex items-center justify-center gap-2.5 rounded-full bg-emerald-600 px-8 py-4 text-[15px] font-extrabold text-white transition-all hover:bg-emerald-700 hover:shadow-[0_10px_36px_-10px_rgba(5,150,105,.6)]"><?php esc_html_e( 'ارسال درخواست مشاوره', 'erfanpro' ); ?><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-send h-4.5 w-4.5 transition-transform group-hover:-translate-x-1" aria-hidden="true"><path d="M14.536 21.686a.5.5 0 0 0 .937-.024l6.5-19a.496.496 0 0 0-.635-.635l-19 6.5a.5.5 0 0 0-.024.937l7.93 3.18a2 2 0 0 1 1.112 1.11z"></path><path d="m21.854 2.147-10.94 10.939"></path></svg></button>
</form>
