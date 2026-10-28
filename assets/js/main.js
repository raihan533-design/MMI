// MMI ENGINEERING - shared behaviour + data loading (content lives in /data/*.json)
(function () {
  'use strict';

  // ---------- helpers (shared with services.js) ----------
  var MMI = window.MMI = {};
  MMI.esc = function (s) {
    return String(s == null ? '' : s).replace(/[&<>"']/g, function (c) {
      return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
    });
  };
  MMI.slug = function (s) { return String(s || '').toLowerCase().replace(/[^a-z0-9-]/g, ''); };
  MMI.icon = function (s) { return /^[a-z0-9 -]+$/i.test(s || '') ? s : 'fas fa-tag'; };
  MMI.img = function (s) { return /^(https?:\/\/|\/|assets\/)/.test(s || '') ? s : ''; };
  MMI.items = function (d) { return (d && Array.isArray(d.items)) ? d.items : []; };
  MMI.json = function (name) {
    return fetch('data/' + name + '.json', { cache: 'no-cache' }).then(function (r) {
      if (!r.ok) throw new Error('HTTP ' + r.status);
      return r.json();
    });
  };
  MMI.refreshAnimations = function () { if (window.AOS && AOS.refreshHard) AOS.refreshHard(); };
  MMI.waNumber = '6585878190';

  // ---------- scroll animations / footer year / navbar ----------
  if (window.AOS) AOS.init({ duration: 700, once: true, offset: 60 });

  var y = document.getElementById('year');
  if (y) y.textContent = new Date().getFullYear();

  var nav = document.getElementById('mainNav');
  function onScroll() { if (nav) nav.classList.toggle('scrolled', window.scrollY > 40); }
  window.addEventListener('scroll', onScroll, { passive: true });
  onScroll();

  // ---------- animated counters ----------
  var counters = document.querySelectorAll('.counter');
  if (counters.length && 'IntersectionObserver' in window) {
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (en) {
        if (!en.isIntersecting) return;
        var el = en.target;
        var target = parseFloat(el.getAttribute('data-target'));
        var decimals = String(target).indexOf('.') > -1 ? 1 : 0;
        var start = null, dur = 1600;
        function step(ts) {
          if (start === null) start = ts;
          var p = Math.min((ts - start) / dur, 1);
          el.textContent = (target * (1 - Math.pow(1 - p, 3))).toFixed(decimals);
          if (p < 1) requestAnimationFrame(step);
        }
        requestAnimationFrame(step);
        io.unobserve(el);
      });
    }, { threshold: 0.4 });
    counters.forEach(function (c) { io.observe(c); });
  }

  // ---------- home hero search -> services page ----------
  var heroForm = document.getElementById('heroSearchForm');
  if (heroForm) {
    heroForm.addEventListener('submit', function (e) {
      e.preventDefault();
      var q = document.getElementById('heroSearch').value.trim();
      window.location.href = 'services.html' + (q ? '?q=' + encodeURIComponent(q) : '');
    });
  }

  // ---------- data-driven content ----------
  function applySettings(s) {
    if (!s) return;
    document.querySelectorAll('[data-setting]').forEach(function (el) {
      var v = s[el.getAttribute('data-setting')];
      if (v) el.textContent = v;
    });
    var wa = String(s.whatsapp || '').replace(/\D/g, '');
    if (wa) MMI.waNumber = wa;
    document.querySelectorAll('[data-setting-link]').forEach(function (a) {
      var kind = a.getAttribute('data-setting-link');
      if (kind === 'tel' && s.phone) a.href = 'tel:' + String(s.phone).replace(/[^\d+]/g, '');
      if (kind === 'mail' && s.email) a.href = 'mailto:' + s.email;
      if (kind === 'wa' && wa) a.href = 'https://wa.me/' + wa;
    });
    var about = document.getElementById('aboutContent');
    if (about && s.about_content) {
      about.innerHTML = String(s.about_content).split(/\n\s*\n/).map(function (p) {
        return p.trim() ? '<p class="text-muted mb-3">' + MMI.esc(p.trim()).replace(/\n/g, '<br>') + '</p>' : '';
      }).join('');
    }
  }

  function faqHtml(items, accId, prefix) {
    return items.map(function (f, i) {
      var n = i + 1;
      return '<div class="accordion-item" data-aos="fade-up" data-aos-delay="' + (n * 50) + '">' +
        '<h2 class="accordion-header"><button class="accordion-button' + (n === 1 ? '' : ' collapsed') + '" type="button" data-bs-toggle="collapse" data-bs-target="#' + prefix + n + '">' +
        '<span class="faq-icon"><i class="fas fa-question"></i></span> ' + MMI.esc(f.question) + '</button></h2>' +
        '<div id="' + prefix + n + '" class="accordion-collapse collapse' + (n === 1 ? ' show' : '') + '" data-bs-parent="#' + accId + '">' +
        '<div class="accordion-body">' + MMI.esc(f.answer).replace(/\n/g, '<br>') + '</div></div></div>';
    }).join('');
  }

  function renderFaqs(items) {
    var home = document.getElementById('homeFaq');
    var all = document.getElementById('faqAccordion');
    if (!items.length) return;
    if (home) home.innerHTML = faqHtml(items.slice(0, 5), 'homeFaq', 'hfaq');
    if (all) all.innerHTML = faqHtml(items, 'faqAccordion', 'faq');
  }

  function renderCategories(items) {
    if (!items.length) return;
    var grid = document.getElementById('homeCategories');
    if (grid) {
      grid.innerHTML = items.map(function (c, i) {
        return '<div class="col-xl-2 col-lg-4 col-md-4 col-6" data-aos="fade-up" data-aos-delay="' + (50 * (i + 1)) + '">' +
          '<a href="services.html?cat=' + MMI.slug(c.slug) + '" class="category-card">' +
          '<div class="category-card-icon"><i class="' + MMI.icon(c.icon) + '"></i></div>' +
          '<h5 class="category-card-title">' + MMI.esc(c.name) + '</h5>' +
          '<p class="category-card-desc">' + MMI.esc(c.description) + '</p>' +
          '<span class="category-card-arrow"><i class="fas fa-arrow-right"></i></span></a></div>';
      }).join('');
    }
    var chips = document.getElementById('heroChips');
    if (chips) {
      chips.innerHTML = items.map(function (c) {
        return '<a href="services.html?cat=' + MMI.slug(c.slug) + '" class="hero-chip"><i class="' + MMI.icon(c.icon) + '"></i><span>' + MMI.esc(c.name) + '</span></a>';
      }).join('');
    }
  }

  var needsFaq = document.getElementById('homeFaq') || document.getElementById('faqAccordion');
  var needsCat = document.getElementById('homeCategories') || document.getElementById('heroChips');
  var jobs = [
    MMI.json('settings').then(applySettings).catch(function () {}),
    needsFaq ? MMI.json('faqs').then(function (d) { renderFaqs(MMI.items(d)); }).catch(function () {}) : null,
    needsCat ? MMI.json('categories').then(function (d) { renderCategories(MMI.items(d)); }).catch(function () {}) : null
  ];
  Promise.all(jobs.filter(Boolean)).then(MMI.refreshAnimations);
})();
