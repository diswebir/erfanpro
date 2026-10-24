/* Blog AJAX live search + category chips + pagination (phase 8) — vanilla JS.
   The hero search box searches posts only (scoped to the blog; the
   site-wide search page is phase 9) through admin-ajax
   (action: erfanpro_blog_search, nonce localised as erfanproBlog).
   Any filter/search change resets to page 1; pagination clicks keep
   the state. Injected cards get the same reveal treatment as anim.js
   (IntersectionObserver + data-anim-final), since anim.js only
   initialises the nodes present at page load. */
(function () {
  "use strict";

  var root = document.getElementById("erfanpro-blog");
  if (!root || typeof window.erfanproBlog === "undefined") return;

  var grid = document.getElementById("erfanpro-blog-grid");
  var pagWrap = document.getElementById("erfanpro-blog-pagination");
  var countEl = document.getElementById("erfanpro-blog-count");
  var searchInput = document.getElementById("erfanpro-blog-search");
  if (!grid || !pagWrap) return;

  /* Class sets mirrored from template-parts/blog/hero.php. */
  var CHIP = {
    on: "rounded-xl px-4 py-2.5 text-[12.5px] font-bold transition-all bg-amber-300 text-emerald-950 shadow-[0_0_24px_rgba(251,191,36,0.35)]",
    off: "rounded-xl px-4 py-2.5 text-[12.5px] font-bold transition-all border border-white/15 bg-white/[0.06] text-white/75 backdrop-blur hover:border-emerald-300/40 hover:text-emerald-100"
  };

  var state = {
    cat: root.getAttribute("data-initial-cat") || "",
    s: searchInput ? searchInput.value : "",
    paged: 1
  };
  var controller = null;

  function syncChips() {
    root.querySelectorAll("[data-blog-cat]").forEach(function (btn) {
      btn.className = (btn.getAttribute("data-blog-cat") || "") === state.cat ? CHIP.on : CHIP.off;
    });
  }

  /* Same reveal engine as anim.js, for AJAX-injected cards. */
  function playAnim(el) {
    var fin = el.getAttribute("data-anim-final");
    el.classList.add("anim-run");
    if (fin !== null) el.style.cssText = fin;
    else { el.style.opacity = "1"; el.style.transform = "none"; el.style.filter = "blur(0px)"; }
    var done = function () {
      el.classList.remove("anim-run");
      el.removeAttribute("data-anim");
      el.removeEventListener("transitionend", done);
    };
    el.addEventListener("transitionend", done);
    setTimeout(done, 1600);
  }

  function reveal(container) {
    var els = container.querySelectorAll("[data-anim]");
    if (!els.length) return;
    els.forEach(function (el) {
      var d = el.getAttribute("data-anim-delay");
      if (d) el.style.setProperty("--anim-delay", d + "s");
    });
    if (!("IntersectionObserver" in window)) {
      els.forEach(playAnim);
      return;
    }
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (en) {
        if (en.isIntersecting) {
          playAnim(en.target);
          io.unobserve(en.target);
        }
      });
    }, { rootMargin: "0px 0px -80px 0px", threshold: 0.02 });
    els.forEach(function (el) { io.observe(el); });
  }

  function apply() {
    if (controller && "abort" in controller) controller.abort();
    controller = "AbortController" in window ? new AbortController() : null;

    grid.style.opacity = "0.45";
    grid.style.pointerEvents = "none";

    var body = new URLSearchParams();
    body.set("action", "erfanpro_blog_search");
    body.set("nonce", window.erfanproBlog.nonce);
    body.set("cat", state.cat);
    body.set("s", state.s);
    body.set("paged", String(state.paged));

    fetch(window.erfanproBlog.ajaxUrl, {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" },
      body: body.toString(),
      credentials: "same-origin",
      signal: controller ? controller.signal : undefined
    })
      .then(function (r) { return r.json(); })
      .then(function (res) {
        if (!res || !res.success || !res.data) return;
        grid.innerHTML = res.data.html;
        pagWrap.innerHTML = res.data.pagination;
        if (countEl) countEl.textContent = res.data.count_text;
        reveal(grid);
      })
      .catch(function (err) {
        if (err && err.name === "AbortError") return;
      })
      .finally(function () {
        grid.style.opacity = "";
        grid.style.pointerEvents = "";
      });
  }

  function changed() {
    state.paged = 1;
    apply();
  }

  /* Category chips. */
  root.querySelectorAll("[data-blog-cat]").forEach(function (btn) {
    btn.addEventListener("click", function () {
      state.cat = btn.getAttribute("data-blog-cat") || "";
      syncChips();
      changed();
    });
  });

  /* Live search — debounced. */
  if (searchInput) {
    var debounce = null;
    searchInput.addEventListener("input", function () {
      clearTimeout(debounce);
      debounce = setTimeout(function () {
        state.s = searchInput.value;
        changed();
      }, 350);
    });
  }

  /* Pagination — delegated, links keep an href fallback without JS. */
  pagWrap.addEventListener("click", function (e) {
    var link = e.target.closest("[data-blog-page]");
    if (!link) return;
    e.preventDefault();
    state.paged = parseInt(link.getAttribute("data-blog-page"), 10) || 1;
    apply();
    var articles = document.getElementById("articles");
    if (articles) articles.scrollIntoView({ behavior: "smooth", block: "start" });
  });

  /* Empty-state reset button (rendered inside the grid). */
  grid.addEventListener("click", function (e) {
    if (!e.target.closest("[data-blog-reset]")) return;
    state.cat = "";
    state.s = "";
    state.paged = 1;
    if (searchInput) searchInput.value = "";
    syncChips();
    apply();
  });
})();
