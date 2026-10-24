/* Header live search (phase 11, decision 20) — vanilla JS.
   Typing in the header search input fetches matching products, projects
   and posts through admin-ajax and shows them in a dropdown. The form
   itself stays a plain GET form, so without JS (or if the request
   fails) submitting still lands on the normal search results page.
   No external requests. */
(function () {
  var bar = document.getElementById('erfanpro-search-bar');
  if (!bar || !window.erfanproSearch) return;
  var input = bar.querySelector('input[type="search"]');
  var box = document.getElementById('erfanpro-search-results');
  if (!input || !box) return;

  var timer = null;
  var lastQuery = '';

  function hide() {
    box.classList.add('hidden');
    box.innerHTML = '';
  }

  function render(results, query) {
    if (!results.length) {
      box.innerHTML = '';
      var empty = document.createElement('div');
      empty.className = 'px-4 py-3 text-[13px] text-slate-500';
      empty.textContent = 'نتیجه‌ای یافت نشد.';
      box.appendChild(empty);
    } else {
      box.innerHTML = '';
      results.forEach(function (item) {
        var link = document.createElement('a');
        link.href = item.url;
        link.className = 'flex items-center justify-between gap-3 px-4 py-2.5 text-[13.5px] text-slate-800 hover:bg-emerald-50';
        var title = document.createElement('span');
        title.textContent = item.title;
        var type = document.createElement('span');
        type.className = 'shrink-0 text-[11px] font-bold text-emerald-700';
        type.textContent = item.type;
        link.appendChild(title);
        link.appendChild(type);
        box.appendChild(link);
      });
    }
    var all = document.createElement('a');
    all.href = '/?s=' + encodeURIComponent(query);
    all.className = 'block border-t border-slate-100 px-4 py-2.5 text-center text-[13px] font-bold text-emerald-700 hover:bg-emerald-50';
    all.textContent = 'مشاهده همه نتایج';
    box.appendChild(all);
    box.classList.remove('hidden');
  }

  input.addEventListener('input', function () {
    var query = input.value.trim();
    if (timer) clearTimeout(timer);
    if (query.length < 2 || query === lastQuery) {
      if (query.length < 2) hide();
      return;
    }
    timer = setTimeout(function () {
      lastQuery = query;
      var body = new FormData();
      body.append('action', 'erfanpro_live_search');
      body.append('nonce', window.erfanproSearch.nonce);
      body.append('q', query);
      fetch(window.erfanproSearch.ajaxUrl, { method: 'POST', body: body, credentials: 'same-origin' })
        .then(function (res) { return res.json(); })
        .then(function (data) {
          if (data && data.success) render(data.data.results || [], query);
        })
        .catch(function () { /* GET fallback stays intact; stay silent. */ });
    }, 250);
  });

  document.addEventListener('click', function (e) {
    if (!bar.contains(e.target)) hide();
  });
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') hide();
  });
})();
