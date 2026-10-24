/* Phase 5 — single product extras: video slide in the gallery and the
 * user-rating form (decision 15). No external requests.
 */
(function () {
  function fa(n) {
    return String(n).replace(/\d/g, function (d) { return '۰۱۲۳۴۵۶۷۸۹'[+d]; });
  }

  /* ---------------- Video slide ---------------- */
  function initVideoSlide() {
    var block = document.getElementById('erfanpro-product-gallery');
    if (!block) return;
    var vthumb = block.querySelector('[data-erfanpro-video-thumb]');
    var video = block.querySelector('video[data-erfanpro-video]');
    var img = block.querySelector('img.gal-fade');
    if (!vthumb || !video || !img) return;

    var total = 0;
    block.querySelectorAll('button').forEach(function (b) {
      if (/^تصویر\s*[۰-۹0-9]+\s*$/.test(b.getAttribute('aria-label') || '')) total++;
    });
    total += 1; /* the video slide itself */

    vthumb.addEventListener('click', function () {
      block.querySelectorAll('button').forEach(function (b) {
        if (b === vthumb) return;
        b.classList.remove('border-emerald-500', 'shadow-[0_4px_16px_-4px_rgba(5,150,105,.45)]');
        b.classList.add('border-transparent', 'opacity-65');
      });
      vthumb.classList.add('border-emerald-500', 'shadow-[0_4px_16px_-4px_rgba(5,150,105,.45)]');
      vthumb.classList.remove('border-transparent', 'opacity-65');
      img.style.display = 'none';
      video.style.display = '';
      var play = video.play();
      if (play && play.catch) play.catch(function () {});
      block.querySelectorAll('span').forEach(function (s) {
        if (/^\s*[۰-۹0-9]+\s*\/\s*[۰-۹0-9]+\s*$/.test(s.textContent)) {
          s.textContent = fa(total) + ' / ' + fa(total);
        }
      });
    });

    /* Any image navigation hides the video again (capture: before main.js). */
    block.addEventListener('click', function (e) {
      var t = e.target.closest ? e.target.closest('button') : null;
      if (!t || t === vthumb) return;
      if (video.style.display === 'none') return;
      video.pause();
      video.style.display = 'none';
      img.style.display = '';
      vthumb.classList.remove('border-emerald-500', 'shadow-[0_4px_16px_-4px_rgba(5,150,105,.45)]');
      vthumb.classList.add('border-transparent', 'opacity-65');
    }, true);
  }

  /* ---------------- Rating form ---------------- */
  function initRating() {
    var box = document.getElementById('erfanpro-rating');
    if (!box || !box.querySelector('[data-rating-input]')) return;
    var stars = Array.prototype.slice.call(box.querySelectorAll('[data-rate]'));
    var submit = box.querySelector('[data-rating-submit]');
    var msg = box.querySelector('[data-rating-msg]');
    var value = 0;

    function paint(n) {
      stars.forEach(function (b, i) {
        var on = i < n;
        b.classList.toggle('text-amber-400', on);
        b.classList.toggle('text-slate-300', !on);
      });
    }
    stars.forEach(function (b) {
      b.addEventListener('mouseenter', function () { paint(+b.getAttribute('data-rate')); });
      b.addEventListener('click', function () {
        value = +b.getAttribute('data-rate');
        paint(value);
        submit.disabled = false;
        submit.classList.remove('opacity-50');
      });
    });
    box.querySelector('[data-rating-input]').addEventListener('mouseleave', function () { paint(value); });
    submit.classList.add('opacity-50');

    submit.addEventListener('click', function () {
      if (!value) return;
      submit.disabled = true;
      msg.textContent = '…';
      var data = new FormData();
      data.append('action', 'erfanpro_submit_rating');
      data.append('nonce', box.getAttribute('data-nonce'));
      data.append('product_id', box.getAttribute('data-product-id'));
      data.append('rating', value);
      fetch(window.erfanproProduct.ajaxUrl, { method: 'POST', body: data, credentials: 'same-origin' })
        .then(function (r) { return r.json(); })
        .then(function (res) {
          if (res.success) {
            msg.textContent = res.data.message || '';
            box.querySelector('[data-rating-input]').style.pointerEvents = 'none';
            submit.style.display = 'none';
          } else {
            msg.textContent = (res.data && res.data.message) || 'خطا';
            submit.disabled = false;
          }
        })
        .catch(function () {
          msg.textContent = 'خطا در ارتباط با سرور';
          submit.disabled = false;
        });
    });
  }

  document.addEventListener('DOMContentLoaded', function () {
    initVideoSlide();
    initRating();
  });
})();
