/* Shop AJAX filters + live search (phase 4) — vanilla JS.
   Category pills (hero + toolbar), pa_* attribute chips, the
   «فقط قابل خرید» toggle, the Persian sort select and the live
   search box all re-render the grid through admin-ajax
   (action: erfanpro_shop_filter, nonce localised as erfanproShop).
   Any filter change resets to page 1; pagination clicks keep the
   state. Injected cards get the same reveal treatment as anim.js
   (IntersectionObserver + data-anim-final), since anim.js only
   initialises the nodes present at page load. */
(function () {
  "use strict";

  var root = document.getElementById("erfanpro-shop");
  if (!root || typeof window.erfanproShop === "undefined") return;

  var grid = document.getElementById("erfanpro-shop-grid");
  var pagWrap = document.getElementById("erfanpro-shop-pagination");
  var countEl = document.getElementById("erfanpro-shop-count");
  var searchInput = document.getElementById("erfanpro-shop-search");
  var sortSelect = document.getElementById("erfanpro-shop-sort");
  var attrsPanel = document.getElementById("erfanpro-shop-attrs");
  var attrsToggle = document.getElementById("erfanpro-shop-attrs-toggle");
  var purchBtn = root.querySelector("[data-shop-purchasable]");
  if (!grid || !pagWrap) return;

  /* Class sets mirrored from template-parts/shop/hero.php + toolbar.php. */
  var PILL = {
    hero: {
      on: "rounded-xl px-3.5 py-2 text-[12.5px] font-bold transition-all bg-emerald-700 text-white shadow-md shadow-emerald-700/25",
      off: "rounded-xl px-3.5 py-2 text-[12.5px] font-bold transition-all border border-slate-200 bg-slate-50 text-slate-600 hover:border-emerald-400 hover:text-emerald-700"
    },
    bar: {
      on: "rounded-full px-5 py-2.5 text-[13px] font-bold transition-all duration-300 bg-emerald-600 text-white shadow-md shadow-emerald-600/25",
      off: "rounded-full px-5 py-2.5 text-[13px] font-bold transition-all duration-300 border border-slate-200 bg-white text-slate-600 hover:border-emerald-400 hover:text-emerald-700"
    }
  };
  var PURCH = {
    on: "flex items-center gap-2 rounded-full px-5 py-2.5 text-[13px] font-bold transition-all duration-300 bg-emerald-600 text-white shadow-md shadow-emerald-600/25",
    off: "flex items-center gap-2 rounded-full px-5 py-2.5 text-[13px] font-bold transition-all duration-300 border border-emerald-200 bg-white text-emerald-700 hover:bg-emerald-50"
  };
  var CHIP = {
    on: "inline-block rounded-full border border-emerald-600 bg-emerald-600 px-3.5 py-1.5 text-[12px] font-bold text-white transition-all shadow-md shadow-emerald-600/25",
    off: "inline-block rounded-full border border-slate-200 bg-slate-50 px-3.5 py-1.5 text-[12px] font-bold text-slate-600 transition-all hover:border-emerald-400 hover:text-emerald-700"
  };

  var state = {
    cat: root.getAttribute("data-initial-cat") || "",
    attrs: {},
    sort: sortSelect ? sortSelect.value : "default",
    purchasable: false,
    s: searchInput ? searchInput.value : "",
    paged: 1
  };
  var controller = null;

  function syncPills() {
    document.querySelectorAll("[data-shop-cat]").forEach(function (btn) {
      var set = btn.closest("[data-shop-hero-pills]") ? PILL.hero : PILL.bar;
      btn.className = btn.getAttribute("data-shop-cat") === state.cat ? set.on : set.off;
    });
  }

  function syncPurch() {
    if (purchBtn) purchBtn.className = state.purchasable ? PURCH.on : PURCH.off;
  }

  function syncChip(cb) {
    var label = cb.closest("label");
    var chip = label ? label.querySelector("[data-attr-chip]") : null;
    if (chip) chip.className = cb.checked ? CHIP.on : CHIP.off;
  }

  function collectAttrs() {
    state.attrs = {};
    root.querySelectorAll("input[data-attr]:checked").forEach(function (cb) {
      var tax = cb.getAttribute("data-attr");
      if (!state.attrs[tax]) state.attrs[tax] = [];
      state.attrs[tax].push(cb.value);
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
    body.set("action", "erfanpro_shop_filter");
    body.set("nonce", window.erfanproShop.nonce);
    body.set("cat", state.cat);
    body.set("sort", state.sort);
    body.set("s", state.s);
    body.set("paged", String(state.paged));
    if (state.purchasable) body.set("purchasable", "1");
    Object.keys(state.attrs).forEach(function (tax) {
      state.attrs[tax].forEach(function (slug) {
        body.append("attrs[" + tax + "][]", slug);
      });
    });

    fetch(window.erfanproShop.ajaxUrl, {
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

  /* Category pills — hero and toolbar stay in sync. */
  document.querySelectorAll("[data-shop-cat]").forEach(function (btn) {
    btn.addEventListener("click", function () {
      var fromHero = !!btn.closest("[data-shop-hero-pills]");
      state.cat = btn.getAttribute("data-shop-cat") || "";
      syncPills();
      changed();
      if (fromHero) root.scrollIntoView({ behavior: "smooth", block: "start" });
    });
  });

  if (purchBtn) {
    purchBtn.addEventListener("click", function () {
      state.purchasable = !state.purchasable;
      syncPurch();
      changed();
    });
  }

  if (sortSelect) {
    sortSelect.addEventListener("change", function () {
      state.sort = sortSelect.value;
      changed();
    });
  }

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

  if (attrsToggle && attrsPanel) {
    attrsToggle.addEventListener("click", function () {
      var open = attrsPanel.classList.toggle("hidden") === false;
      attrsToggle.setAttribute("aria-expanded", open ? "true" : "false");
    });
  }

  root.querySelectorAll("input[data-attr]").forEach(function (cb) {
    cb.addEventListener("change", function () {
      syncChip(cb);
      collectAttrs();
      changed();
    });
  });

  /* Pagination — delegated, links keep an href fallback without JS. */
  pagWrap.addEventListener("click", function (e) {
    var link = e.target.closest("[data-shop-page]");
    if (!link) return;
    e.preventDefault();
    state.paged = parseInt(link.getAttribute("data-shop-page"), 10) || 1;
    apply();
    root.scrollIntoView({ behavior: "smooth", block: "start" });
  });

  /* Empty-state reset button (rendered inside the grid). */
  grid.addEventListener("click", function (e) {
    if (!e.target.closest("[data-shop-reset]")) return;
    state.cat = "";
    state.attrs = {};
    state.sort = "default";
    state.purchasable = false;
    state.s = "";
    state.paged = 1;
    if (searchInput) searchInput.value = "";
    if (sortSelect) sortSelect.value = "default";
    root.querySelectorAll("input[data-attr]:checked").forEach(function (cb) {
      cb.checked = false;
      syncChip(cb);
    });
    syncPills();
    syncPurch();
    apply();
  });
})();
