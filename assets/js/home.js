/* Home services accordion (phase 3) — vanilla JS.
   Clicking a service button activates it (active classes, description +
   mobile image expand) and swaps the sticky desktop preview
   (image, icon, title, description, "n / total" counter). */
(function () {
  "use strict";
  var FA = "۰۱۲۳۴۵۶۷۸۹";
  function toFa(s) { return String(s).replace(/[0-9]/g, function (d) { return FA[+d]; }); }

  function slideDown(el) {
    el.style.display = "";
    el.style.height = "0px";
    el.style.opacity = "0";
    void el.offsetHeight;
    el.style.transition = "height 0.38s cubic-bezier(0.22,1,0.36,1), opacity 0.3s ease";
    el.style.height = el.scrollHeight + "px";
    el.style.opacity = "1";
    setTimeout(function () {
      el.style.transition = ""; el.style.height = ""; el.style.opacity = "";
    }, 420);
  }
  function slideUp(el) {
    el.style.height = el.scrollHeight + "px";
    el.style.opacity = "1";
    void el.offsetHeight;
    el.style.transition = "height 0.38s cubic-bezier(0.22,1,0.36,1), opacity 0.3s ease";
    el.style.height = "0px";
    el.style.opacity = "0";
    setTimeout(function () {
      el.style.display = "none";
      el.style.transition = ""; el.style.height = ""; el.style.opacity = "";
    }, 400);
  }

  document.addEventListener("DOMContentLoaded", function () {
    var wrap = document.querySelector("[data-erfanpro-services]");
    if (!wrap) return;
    var btns = Array.prototype.slice.call(wrap.querySelectorAll("[data-svc-btn]"));
    if (!btns.length) return;
    var preview = wrap.querySelector("[data-svc-preview]");

    function activate(idx) {
      btns.forEach(function (b, j) {
        var on = j === idx;
        b.classList.toggle("bg-emerald-50", on);
        b.classList.toggle("rounded-2xl", on);
        b.classList.toggle("hover:bg-slate-50", !on);
        b.setAttribute("aria-expanded", on ? "true" : "false");
        var num = b.querySelector("[data-svc-num]");
        if (num) {
          num.classList.toggle("text-emerald-700", on);
          num.classList.toggle("text-slate-400", !on);
        }
        var ttl = b.querySelector("[data-svc-title]");
        if (ttl) {
          ttl.classList.toggle("text-emerald-950", on);
          ttl.classList.toggle("text-slate-800", !on);
        }
        var bar = b.querySelector("[data-svc-bar]");
        if (bar) bar.classList.toggle("h-3/4", on);
        var ic = b.querySelector("[data-svc-icon]");
        if (ic) {
          ic.classList.toggle("scale-110", on);
          ic.classList.toggle("text-emerald-700", on);
          ic.classList.toggle("text-slate-400", !on);
          ic.classList.toggle("group-hover:text-emerald-600", !on);
        }
        var d = b.querySelector("[data-svc-desc]");
        if (d) { if (on) slideDown(d); else if (d.style.display !== "none") slideUp(d); }
        var im = b.querySelector("[data-svc-mimg]");
        if (im) { if (on) slideDown(im); else if (im.style.display !== "none") slideUp(im); }
      });

      if (preview) {
        var b = btns[idx];
        var img = b.getAttribute("data-svc-img");
        var pImg = preview.querySelector("[data-svc-pimg]");
        if (pImg && img) { pImg.src = img; pImg.alt = b.getAttribute("data-svc-name") || ""; }
        var pTitle = preview.querySelector("[data-svc-ptitle]");
        if (pTitle) pTitle.textContent = b.getAttribute("data-svc-name") || "";
        var pDesc = preview.querySelector("[data-svc-pdesc]");
        if (pDesc) pDesc.textContent = b.getAttribute("data-svc-text") || "";
        var pCount = preview.querySelector("[data-svc-pcount]");
        if (pCount) pCount.textContent = toFa(idx + 1) + " / " + toFa(btns.length);
        var pIcon = preview.querySelector("[data-svc-picon]");
        var icSrc = b.querySelector("[data-svc-iconwrap]");
        if (pIcon && icSrc) {
          pIcon.innerHTML = icSrc.innerHTML
            .replace(/scale-110|text-emerald-700|text-slate-400|group-hover:text-emerald-600/g, "")
            .replace("h-6 w-6", "h-7 w-7 text-emerald-200")
            .replace(/\s+/g, " ");
        }
      }
    }

    btns.forEach(function (b, i) {
      b.addEventListener("click", function () { activate(i); });
    });
  });
})();
