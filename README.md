# erfan2027pro (erfanpro)

قالب وردپرس کلاسیک PHP برای «عرفان صنعت اصفهان» — ساخته‌شده از روی رفرنس استاتیک
`main-erfansanat-static` (۳۷ برگه) و بلوپرینت معماری زنده erfansanat.com.

## معماری

- قالب کلاسیک مستقل، **بدون وابستگی به Elementor**؛ داده‌ها مستقیم از همان
  دیتابیس و متاکی‌های فعلی خوانده می‌شوند (هیچ متاکی تغییر نام نمی‌دهد):
  - محصولات: WooCommerce `product` + متاباکس meta-4 (`about-productt`, `faq`/`mfaq`,
    `product-catalog-file`, `galerry_produ`, کدهای `gs1_id`/`national_code`/...)
    و ۱۸ اتریبیوت `pa_*`
  - پروژه‌ها: JetEngine CPT `projects` + متاباکس meta-2 (`gallery-projects`,
    `info-project`, `finish-pro-time`, ...) و تکسونومی‌های `city` و `project_type`
  - خدمات: CPT تازه `services` (ثبت‌شده در `inc/cpt-services.php`)
- تنظیمات سراسری در یک کلید `erfanpro_options` (صفحه «تنظیمات erfanpro» در پیشخوان).
- چهار لوکیشن منو مطابق منوهای بلوپرینت: `mega`, `mobile`, `landing`, `company`.
- مدل فروش ترکیبی خودکار: محصول قیمت‌دار ← سبد خرید ووکامرس؛ بی‌قیمت ← کارت استعلام.
- صفر ریکوئست خارجی: فونت‌های Vazirmatn و Lalezar لوکال، با قابلیت آپلود فونت
  سفارشی از تنظیمات قالب.
- لایه انیمیشن عیناً از رفرنس: `assets/js/anim.js` + `assets/css/anim.css` و
  هلپر PHP `erfanpro_reveal()` در `inc/helpers.php`.
- CSS اصلی از سورس Tailwind ساخته می‌شود (`assets/css/app.build.css`)؛ تا وقتی
  بیلد آماده نیست، CSS کامپایل‌شده رفرنس (`assets/css/reference-app.css`) فال‌بک است.

## محیط

- نصب و تست: https://new.erfansanat.com (داده‌های فعلی سایت کامل حفظ می‌شوند)
- سینک: `~/workspace/wordpress/sync-theme-live.sh erfanpro`
- سند پلن و چک‌لیست ۳۹ تصمیم: `wp-theme-conversion-plan.md` (بخش ۹)
- پایش: کرون `erfanpro-audit-watch` هر ۱۰ دقیقه (اعمال تصمیم‌ها، شباهت به رفرنس، پوشش پنل ادمین)

## فاز ۱ — زیرساخت داده (تکمیل‌شده)

- `inc/meta-format.php`: خواندن/نوشتن با فرمت دقیق JetEngine —
  ریپیترها به‌شکل `item-N`، گالری‌ها رشته‌ی `id,id`، سلکت‌های چندتایی آرایه؛
  خواننده‌ها هر سه شکل (سریالایز PHP، رشته JSON، لیست ویرگولی) را می‌فهمند.
- `inc/metaboxes.php`: متاباکس‌های محصول و پروژه با **نام دقیق** کلیدهای بلوپرینت
  (از جمله املاهای تاریخی: `galerry_produ`، `three-image`، `foure-image`،
  `vaule_of_variable`، `english_titl`، `gallery-filed`) + کلیدهای قدیمی دیتابیس
  (`usage_product`، `product-feature`، `image-2` تا `image-6`، ...).
- `inc/metaboxes-services.php`: متاباکس‌های CPT خدمات — گالری، ویژگی‌ها، مراحل،
  FAQ، پروژه‌های مرتبط (BO-008).
- `inc/cpt-projects-fallback.php`: ثبت CPT ‏`projects`‏ و تکسونومی‌های `city` و
  `project_type` **فقط وقتی JetEngine آن‌ها را نساخته باشد** (بدون تداخل).
- `inc/options.php`: تکمیل آپشن‌پیج — نمادهای اعتماد (BO-001)، شمارنده‌های خانه
  با عدد واقعی + آفست (BO-002)، متن‌های ۶ سکشن خانه (BO-003)، آپلود فونت سفارشی
  (BO-004).
- `inc/view-counter.php`: شمارنده بازدید یکتا روی `number-viewc`/`number-view` +
  متاباکس سایدبار با دکمه ریست AJAX (BO-007، تصمیم ۱۶).
- `inc/multilang.php`: متاباکس لینک دستی en/ar + تگ‌های hreflang؛ کلیدهای
  `hrefmeta_*` دست‌نخورده می‌مانند (BO-005، تصمیم ۹).
- `inc/leads.php`: پست‌تایپ داخلی `erfanpro_lead` + تابع `erfanpro_save_lead()`
  (اعتبارسنجی موبایل ایرانی با ارقام فارسی/عربی، هانی‌پات) + اکشن
  `erfanpro_lead_saved` برای پیامک آینده + نقطه تزریق کپچا (BO-006، تصمیم ۲۴).
- دارایی‌های ادمین: `assets/js/admin-metabox.js` و `assets/css/admin-metabox.css`
  (ریپیترها و انتخاب‌گر رسانه، بدون وابستگی).
- اعتبارسنجی: `php8.3 -l` روی همه فایل‌ها + ۲۹ تست کاربردی لایه داده (هسته:
  رفت‌وبرگشت فرمت JetEngine) — همه سبز.
