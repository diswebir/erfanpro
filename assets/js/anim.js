/* Animation runtime for the static mirror — vanilla JS recreation of the
   original framer-motion behaviour:
   - Reveal: hidden {opacity 0, y 42, blur 6px} -> visible, .9s cubic-bezier(.22,1,.36,1),
     viewport margin -80px, once. Per-element recorded initial/final styles win when baked.
   - Mount animations in heroes use the same engine with a stagger delay.
   - About counters: 0 -> value in 2.2s, ease [0.16,1,0.3,1], Persian digits.
   - Home hero parallax: bg y 0%->14% scale 1.02->1.14; copy y 0%->26%, opacity ->0 by 70%.
   - Navbar scrolled state at scrollY > 28 (class swap, exact source classes). */
(function () {
  "use strict";
  window.__animInit = true;

  function cubicBezier(x1, y1, x2, y2) {
    var cx = 3 * x1, bx = 3 * (x2 - x1) - cx, ax = 1 - cx - bx;
    var cy = 3 * y1, by = 3 * (y2 - y1) - cy, ay = 1 - cy - by;
    function sx(t) { return ((ax * t + bx) * t + cx) * t; }
    function sy(t) { return ((ay * t + by) * t + cy) * t; }
    function dx(t) { return (3 * ax * t + 2 * bx) * t + cx; }
    return function (x) {
      var t = x;
      for (var i = 0; i < 5; i++) { var s = sx(t) - x; if (Math.abs(s) < 1e-4) return sy(t); var d = dx(t); if (Math.abs(d) < 1e-4) break; t -= s / d; }
      return sy(t);
    };
  }
  var easeOutCounter = cubicBezier(0.16, 1, 0.3, 1);
  var FA = "۰۱۲۳۴۵۶۷۸۹";
  function toFa(s) { return String(s).replace(/[0-9]/g, function (d) { return FA[+d]; }); }

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

  function initReveals() {
    var els = document.querySelectorAll("[data-anim]");
    if (!els.length) return;
    els.forEach(function (el) {
      var init = el.getAttribute("data-anim-init");
      if (init) el.style.cssText = init;
      var delay = el.getAttribute("data-anim-delay");
      if (delay) el.style.setProperty("--anim-delay", delay + "s");
    });
    var mounts = [];
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (en) {
        if (en.isIntersecting) { playAnim(en.target); io.unobserve(en.target); }
      });
    }, { rootMargin: "0px 0px -80px 0px", threshold: 0.02 });
    var ioRepeat = new IntersectionObserver(function (entries) {
      entries.forEach(function (en) {
        var el = en.target;
        if (en.isIntersecting) playAnim(el);
        else {
          var init = el.getAttribute("data-anim-init");
          el.classList.add("anim-run");
          if (init) el.style.cssText = init;
          el.setAttribute("data-anim", "");
        }
      });
    }, { rootMargin: "0px 0px -80px 0px", threshold: 0.02 });
    els.forEach(function (el) {
      if (el.getAttribute("data-anim-kind") === "mount") mounts.push(el);
      else if (el.hasAttribute("data-anim-repeat")) ioRepeat.observe(el);
      else io.observe(el);
    });
    mounts.forEach(function (el) { playAnim(el); });
  }

  function initCounters() {
    var els = document.querySelectorAll("[data-counter-to]");
    if (!els.length) return;
    els.forEach(function (el) {
      var fin = el.getAttribute("data-counter-final") || "";
      var suffix = fin.replace(/^[۰-۹0-9]+/, "");
      el.textContent = toFa(0) + suffix;
    });
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (en) {
        if (!en.isIntersecting) return;
        io.unobserve(en.target);
        var el = en.target;
        var target = parseFloat(el.getAttribute("data-counter-to"));
        var fin = el.getAttribute("data-counter-final") || "";
        var suffix = fin.replace(/^[۰-۹0-9]+/, "");
        var delay = parseFloat(el.getAttribute("data-counter-delay") || "0") * 1000;
        var t0 = null;
        setTimeout(function () {
          function step(ts) {
            if (!t0) t0 = ts;
            var p = Math.min((ts - t0) / 2200, 1);
            var v = Math.round(target * easeOutCounter(p));
            el.textContent = toFa(v) + suffix;
            if (p < 1) requestAnimationFrame(step);
            else el.textContent = fin;
          }
          requestAnimationFrame(step);
        }, delay);
      });
    }, { rootMargin: "0px 0px -60px 0px", threshold: 0.2 });
    els.forEach(function (el) { io.observe(el); });
  }

  function initParallax() {
    var bg = document.querySelector("[data-plx-bg]");
    var copy = document.querySelector("[data-plx-copy]");
    if (!bg && !copy) return;
    var hero = (bg || copy).closest("section");
    if (!hero) return;
    var ticking = false;
    function update() {
      ticking = false;
      var h = hero.offsetHeight || 1;
      var p = Math.max(0, Math.min(window.scrollY / h, 1));
      if (bg) bg.style.transform = "translateY(" + (14 * p).toFixed(2) + "%) scale(" + (1.02 + 0.12 * p).toFixed(4) + ")";
      if (copy) {
        copy.style.transform = "translateY(" + (26 * p).toFixed(2) + "%)";
        copy.style.opacity = String(1 - Math.min(p / 0.7, 1));
      }
    }
    window.addEventListener("scroll", function () {
      if (!ticking) { ticking = true; requestAnimationFrame(update); }
    }, { passive: true });
    update();
  }

  function initNavbar() {
    var header = document.querySelector("header[data-cls-scrolled]");
    if (!header) return;
    var top = header.getAttribute("data-cls-top");
    var scr = header.getAttribute("data-cls-scrolled");
    function onScroll() {
      var want = window.scrollY > 28 ? scr : top;
      if (want && header.className !== want) header.className = want;
    }
    window.addEventListener("scroll", onScroll, { passive: true });
    onScroll();
  }

  document.addEventListener("DOMContentLoaded", function () {
    initReveals();
    initCounters();
    initParallax();
    initNavbar();
  });
})();
