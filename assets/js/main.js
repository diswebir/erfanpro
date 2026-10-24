/* Static-site interactions for the ESE offline mirror (vanilla JS, no framework).
   Replicates the small interactive behaviours of the original React app:
   mobile menu, image galleries with lightbox, and the contact form. */
(function () {
  "use strict";
  var FA = "۰۱۲۳۴۵۶۷۸۹";
  function toFa(s) {
    return String(s).replace(/[0-9]/g, function (d) { return FA[+d]; });
  }

  /* ---------------- Mobile menu ---------------- */
  function initMobileMenu() {
    var btn = document.querySelector('header button[aria-label="منو"]');
    var header = document.querySelector("header");
    if (!btn || !header) return;
    var stale = document.getElementById("static-mobile-menu");
    if (stale) stale.remove();
    var desktopNav = header.querySelector("nav");
    var telLink = header.querySelector('a[href^="tel:"]');

    var panel = document.createElement("nav");
    panel.id = "static-mobile-menu";
    panel.setAttribute("aria-label", "منوی موبایل");
    panel.className =
      "mmenu-panel fixed inset-x-4 top-20 z-40 rounded-3xl border border-emerald-100 bg-white/98 p-4 shadow-2xl backdrop-blur-2xl xl:hidden";
    panel.style.display = "none";

    if (desktopNav) {
      desktopNav.querySelectorAll("a").forEach(function (a, i) {
        var row = document.createElement("div");
        row.className = "mmenu-item";
        row.style.transitionDelay = 0.04 * i + "s";
        var link = document.createElement("a");
        link.href = a.getAttribute("href");
        link.className =
          "flex items-center justify-between rounded-2xl px-5 py-3 text-[14.5px] font-semibold text-slate-800 hover:bg-emerald-50 hover:text-emerald-800";
        link.innerHTML =
          "<span>" + a.textContent.trim() + '</span><span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>';
        row.appendChild(link);
        panel.appendChild(row);
      });
    }
    if (telLink) {
      var call = document.createElement("a");
      call.href = telLink.getAttribute("href");
      call.className =
        "mt-2.5 flex items-center justify-center gap-2.5 rounded-2xl bg-emerald-600 px-5 py-3.5 text-[14.5px] font-bold text-white";
      call.textContent = "مشاوره رایگان: " + telLink.textContent.trim();
      panel.appendChild(call);
    }
    header.parentNode.insertBefore(panel, header.nextSibling);

    var open = false;
    function showMenu() {
      panel.style.display = "block";
      requestAnimationFrame(function () {
        requestAnimationFrame(function () { panel.classList.add("open"); });
      });
    }
    function hideMenu() {
      panel.classList.remove("open");
      setTimeout(function () { if (!open) panel.style.display = "none"; }, 360);
    }
    btn.addEventListener("click", function () {
      open = !open;
      if (open) showMenu(); else hideMenu();
    });
    panel.addEventListener("click", function (e) {
      if (e.target.closest("a")) {
        open = false;
        hideMenu();
      }
    });
  }

  /* ---------------- Galleries ---------------- */
  function thumbButtons(scope) {
    return Array.prototype.slice.call(scope.querySelectorAll("button")).filter(function (b) {
      return /^تصویر\s*[\d۰-۹]+\s*$/.test(b.getAttribute("aria-label") || "");
    });
  }
  function initGallery(mainImg) {
    // find the block that contains both the main image and the thumbnails
    var block = mainImg;
    for (var i = 0; i < 6 && block.parentElement; i++) {
      block = block.parentElement;
      if (thumbButtons(block).length > 1) break;
    }
    var thumbs = thumbButtons(block);
    if (!thumbs.length) return;
    var sources = thumbs.map(function (b) {
      var im = b.querySelector("img");
      return im ? im.getAttribute("src") : null;
    });
    var counter = null;
    block.querySelectorAll("span").forEach(function (s) {
      if (/^\s*[۰-۹0-9]+\s*\/\s*[۰-۹0-9]+\s*$/.test(s.textContent)) counter = s;
    });
    var active = 0;
    var firstRender = true;
    mainImg.classList.add("gal-fade");
    function applyRender() {
      mainImg.src = sources[active];
      thumbs.forEach(function (b, i) {
        b.classList.toggle("border-emerald-500", i === active);
        b.classList.toggle("shadow-[0_4px_16px_-4px_rgba(5,150,105,.45)]", i === active);
        b.classList.toggle("border-transparent", i !== active);
        b.classList.toggle("opacity-65", i !== active);
      });
      if (counter) counter.textContent = toFa(active + 1) + " / " + toFa(sources.length);
      mainImg.style.opacity = "";
      mainImg.style.transform = "";
    }
    function render() {
      if (firstRender) { firstRender = false; applyRender(); return; }
      mainImg.style.opacity = "0";
      mainImg.style.transform = "scale(1.04)";
      setTimeout(applyRender, 220);
    }
    thumbs.forEach(function (b, i) {
      b.addEventListener("click", function () {
        active = i;
        render();
      });
    });
    var prev = block.querySelector('button[aria-label="تصویر قبلی"]');
    var next = block.querySelector('button[aria-label="تصویر بعدی"]');
    if (prev) prev.addEventListener("click", function () { active = (active - 1 + sources.length) % sources.length; render(); });
    if (next) next.addEventListener("click", function () { active = (active + 1) % sources.length; render(); });

    // simple lightbox
    function openLightbox() {
      var overlay = document.createElement("div");
      overlay.className =
        "fixed inset-0 z-[100] flex items-center justify-center bg-emerald-950/95 p-4 backdrop-blur-md lg:p-10";
      var big = document.createElement("img");
      big.src = sources[active];
      big.alt = mainImg.alt || "";
      big.className = "max-h-[80vh] max-w-full rounded-2xl object-contain shadow-2xl";
      overlay.appendChild(big);
      overlay.addEventListener("click", function () { overlay.remove(); });
      document.addEventListener("keydown", function esc(e) {
        if (e.key === "Escape") { overlay.remove(); document.removeEventListener("keydown", esc); }
      });
      document.body.appendChild(overlay);
    }
    mainImg.style.cursor = "zoom-in";
    mainImg.addEventListener("click", openLightbox);
    var expandBtn = block.querySelector('button[aria-label="نمایش بزرگ‌تر"]');
    if (expandBtn) expandBtn.addEventListener("click", openLightbox);
  }

  /* ---------------- Contact form ---------------- */
  function initForms() {
    document.querySelectorAll("form").forEach(function (form) {
      form.addEventListener("submit", function (e) {
        e.preventDefault();
        if (form.dataset.sent) return;
        form.dataset.sent = "1";
        var note = document.createElement("p");
        note.className =
          "mt-4 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-center text-[14px] font-bold text-emerald-800";
        note.textContent =
          "پیام شما ثبت شد. در نسخه آفلاین، برای ارسال مستقیم با ما تماس بگیرید یا از دکمه واتس‌اپ استفاده کنید.";
        form.appendChild(note);
      });
    });
  }

  /* ---------------- Listing filters (products / blog / projects) ---------------- */
  function initListingFilter() {
    var main = document.querySelector("main");
    if (!main) return;
    // candidate cards: anchors to detail pages that contain an image
    var cards = Array.prototype.slice.call(main.querySelectorAll("a")).filter(function (a) {
      var href = a.getAttribute("href") || "";
      return /\.html($|\?)/.test(href) && a.querySelector("img") && a.querySelector("h2,h3,h4");
    });
    if (cards.length < 3) return;
    // category label of a card = text of the smallest element whose text is short
    // and appears as a filter-button label
    var buttons = Array.prototype.slice.call(main.querySelectorAll("button")).filter(function (b) {
      return b.textContent.trim().length > 0 && b.textContent.trim().length < 40;
    });
    var labelCount = {};
    buttons.forEach(function (b) {
      var t = b.textContent.trim();
      labelCount[t] = (labelCount[t] || 0) + 1;
    });
    function cardCategory(card) {
      var best = null;
      card.querySelectorAll("span,p,div").forEach(function (el) {
        if (el.children.length) return;
        var t = el.textContent.trim();
        if (t && labelCount[t] && t !== "همه") best = t;
      });
      return best;
    }
    var cats = cards.map(cardCategory);
    var known = {};
    cats.forEach(function (c) { if (c) known[c] = true; });
    var catNames = Object.keys(known);
    if (catNames.length < 2) return;
    var filterBtns = buttons.filter(function (b) {
      var t = b.textContent.trim();
      return t === "همه" || known[t];
    });
    if (!filterBtns.length) return;

    var style = document.createElement("style");
    style.textContent =
      ".static-filter-active{background-color:#059669!important;color:#fff!important;border-color:#059669!important}";
    document.head.appendChild(style);

    var searchInput = main.querySelector('input[type="search"], input[type="text"]');
    function apply(cat, query) {
      var shown = 0;
      cards.forEach(function (card, i) {
        var okCat = !cat || cat === "همه" || cats[i] === cat;
        var okQ = !query || card.textContent.indexOf(query) !== -1;
        var vis = okCat && okQ;
        card.style.display = vis ? "" : "none";
        // also hide a wrapping <li>/grid cell if the card is wrapped
        var wrap = card.parentElement;
        if (wrap && wrap.children.length === 1 && wrap.tagName !== "MAIN") {
          wrap.style.display = card.style.display;
        }
        if (vis) {
          card.classList.remove("card-in");
          card.style.animationDelay = Math.min(shown * 0.04, 0.4) + "s";
          void card.offsetWidth;
          card.classList.add("card-in");
          shown++;
        }
      });
      filterBtns.forEach(function (b) {
        b.classList.toggle("static-filter-active", b.textContent.trim() === cat);
      });
    }
    var currentCat = "همه";
    filterBtns.forEach(function (b) {
      b.addEventListener("click", function () {
        currentCat = b.textContent.trim();
        apply(currentCat, searchInput ? searchInput.value.trim() : "");
      });
    });
    if (searchInput) {
      searchInput.addEventListener("input", function () {
        apply(currentCat, searchInput.value.trim());
      });
    }
    // honour ?category= (from breadcrumb links)
    var q = new URLSearchParams(location.search).get("category");
    if (q && known[q]) {
      currentCat = q;
      apply(currentCat, "");
    }
  }

  /* ---------- product detail tabs ---------- */
  function initTabs() {
    document.querySelectorAll("[data-tab-btn]").forEach(function (btn) {
      btn.addEventListener("click", function () {
        var card = btn;
        while (card && !(card.querySelector && card.querySelector("[data-tab-panel]"))) card = card.parentElement;
        if (!card) return;
        var idx = btn.getAttribute("data-tab-btn");
        card.querySelectorAll("[data-tab-btn]").forEach(function (b) {
          var on = b.getAttribute("data-cls-on"), off = b.getAttribute("data-cls-off");
          if (b === btn) { if (on) b.className = on; } else if (off) b.className = off;
        });
        card.querySelectorAll("[data-tab-panel]").forEach(function (p) {
          var show = p.getAttribute("data-tab-panel") === idx;
          p.style.display = show ? "" : "none";
          if (show) {
            p.classList.remove("panel-in");
            void p.offsetWidth;
            p.classList.add("panel-in");
          }
        });
      });
    });
  }

  /* ---------- FAQ accordion (single-open, height-animated like the source) ---------- */
  function initFaq() {
    var EASE = "cubic-bezier(0.22, 1, 0.36, 1)";
    function closeAns(a) {
      if (!a || a.style.display === "none") return;
      a.style.height = a.scrollHeight + "px";
      a.style.opacity = "1";
      void a.offsetHeight;
      a.style.transition = "height 0.38s " + EASE + ", opacity 0.3s ease";
      a.style.height = "0px";
      a.style.opacity = "0";
      setTimeout(function () {
        a.style.display = "none";
        a.style.transition = "";
        a.style.height = "";
        a.style.opacity = "";
      }, 400);
    }
    function openAns(a) {
      if (!a) return;
      a.style.display = "";
      a.style.height = "0px";
      a.style.opacity = "0";
      void a.offsetHeight;
      a.style.transition = "height 0.38s " + EASE + ", opacity 0.3s ease";
      a.style.height = a.scrollHeight + "px";
      a.style.opacity = "1";
      setTimeout(function () {
        a.style.transition = "";
        a.style.height = "";
        a.style.opacity = "";
      }, 400);
    }
    document.querySelectorAll("[data-faq-btn]").forEach(function (btn) {
      btn.addEventListener("click", function () {
        var item = btn.closest("[data-faq-item]");
        if (!item) return;
        var panel = item;
        while (panel && !panel.hasAttribute("data-tab-panel")) panel = panel.parentElement;
        var ans = item.querySelector("[data-faq-answer]");
        var willOpen = ans && ans.style.display === "none";
        if (panel) {
          panel.querySelectorAll("[data-faq-item]").forEach(function (it) {
            if (it === item) return;
            closeAns(it.querySelector("[data-faq-answer]"));
            var ic = it.querySelector("[data-icon-on]");
            if (ic && ic.getAttribute("data-icon-off")) ic.className = ic.getAttribute("data-icon-off");
          });
        }
        if (!ans) return;
        if (willOpen) {
          openAns(ans);
          var icon = item.querySelector("[data-icon-on]");
          if (icon) icon.className = icon.getAttribute("data-icon-on");
        } else {
          closeAns(ans);
          var ic2 = item.querySelector("[data-icon-on]");
          if (ic2 && ic2.getAttribute("data-icon-off")) ic2.className = ic2.getAttribute("data-icon-off");
        }
      });
    });
  }

  document.addEventListener("DOMContentLoaded", function () {
    initMobileMenu();
    document.querySelectorAll("img.cursor-zoom-in").forEach(initGallery);
    initForms();
    initListingFilter();
    initTabs();
    initFaq();
  });
})();
