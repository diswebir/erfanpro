/* Projects AJAX filters (phase 6) — vanilla JS.
   City chips (archive row) and project_type pills (hero + archive row)
   re-render the grid through admin-ajax
   (action: erfanpro_projects_filter, nonce localised as erfanproProjects).
   Any filter change resets to page 1; pagination clicks keep the
   state. Injected cards get the same reveal treatment as anim.js
   (IntersectionObserver + data-anim-final), since anim.js only
   initialises the nodes present at page load. */
(function () {
  "use strict";

  var root = document.querySelector("[data-erfanpro-projects]");
  if (!root || typeof window.erfanproProjects === "undefined") return;

  var grid = document.getElementById("erfanpro-projects-grid");
  var pagWrap = document.getElementById("erfanpro-projects-pagination");
  var countEl = document.getElementById("erfanpro-projects-count");
  if (!grid || !pagWrap) return;

  /* Class sets mirrored from template-parts/projects/hero.php + toolbar.php. */
  var CITY = {
    on: "rounded-xl px-3.5 py-1.5 text-xs font-bold transition-all bg-emerald-800 text-white shadow-xs",
    off: "rounded-xl px-3.5 py-1.5 text-xs font-bold transition-all border border-slate-200 bg-white text-slate-600 hover:border-emerald-300 hover:text-emerald-700"
  };
  var TYPE = {
    hero: {
      on: "flex items-center gap-1.5 rounded-xl px-3.5 py-1.5 text-[12px] font-bold transition-all bg-emerald-700 text-white shadow-md shadow-emerald-700/25",
      off: "flex items-center gap-1.5 rounded-xl px-3.5 py-1.5 text-[12px] font-bold transition-all border border-slate-200 bg-white text-slate-600 hover:border-emerald-400 hover:text-emerald-700"
    },
    bar: {
      on: "rounded-full px-5 py-2.5 text-[13.5px] font-bold transition-all duration-300 bg-emerald-600 text-white shadow-md shadow-emerald-600/25",
      off: "rounded-full px-5 py-2.5 text-[13.5px] font-bold transition-all duration-300 border border-slate-200 bg-white text-slate-600 hover:border-emerald-400 hover:text-emerald-700"
    }
  };

  var state = {
    city: root.getAttribute("data-initial-city") || "",
    type: root.getAttribute("data-initial-type") || "",
    paged: 1
  };
  var controller = null;

  function syncChips() {
    document.querySelectorAll("[data-projects-city]").forEach(function (btn) {
      btn.className = btn.getAttribute("data-projects-city") === state.city ? CITY.on : CITY.off;
    });
    document.querySelectorAll("[data-projects-type]").forEach(function (btn) {
      var set = btn.closest("[data-projects-hero-types]") ? TYPE.hero : TYPE.bar;
      btn.className = btn.getAttribute("data-projects-type") === state.type ? set.on : set.off;
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
    controller = new AbortController();
    var data = new FormData();
    data.append("action", "erfanpro_projects_filter");
    data.append("nonce", window.erfanproProjects.nonce);
    data.append("city", state.city);
    data.append("type", state.type);
    data.append("paged", state.paged);
    fetch(window.erfanproProjects.ajaxUrl, {
      method: "POST",
      body: data,
      credentials: "same-origin",
      signal: controller.signal
    })
      .then(function (r) { return r.json(); })
      .then(function (res) {
        if (!res.success) return;
        grid.innerHTML = res.data.html;
        pagWrap.innerHTML = res.data.pagination;
        if (countEl) countEl.textContent = res.data.count_text;
        reveal(grid);
        grid.scrollIntoView({ behavior: "smooth", block: "start" });
      })
      .catch(function (e) {
        if (e && e.name === "AbortError") return;
      });
  }

  document.querySelectorAll("[data-projects-city]").forEach(function (btn) {
    btn.addEventListener("click", function () {
      state.city = btn.getAttribute("data-projects-city") || "";
      state.paged = 1;
      syncChips();
      apply();
    });
  });
  document.querySelectorAll("[data-projects-type]").forEach(function (btn) {
    btn.addEventListener("click", function () {
      state.type = btn.getAttribute("data-projects-type") || "";
      state.paged = 1;
      syncChips();
      apply();
    });
  });

  grid.addEventListener("click", function (e) {
    var reset = e.target.closest ? e.target.closest("[data-projects-reset]") : null;
    if (reset) {
      state.city = "";
      state.type = "";
      state.paged = 1;
      syncChips();
      apply();
    }
  });

  pagWrap.addEventListener("click", function (e) {
    var link = e.target.closest ? e.target.closest("[data-projects-page]") : null;
    if (!link) return;
    e.preventDefault();
    state.paged = parseInt(link.getAttribute("data-projects-page"), 10) || 1;
    apply();
  });
})();
