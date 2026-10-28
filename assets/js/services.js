// Services page: loads data/services.json + categories.json, then search / category filter / dark mode
(function () {
  'use strict';

  var MMI = window.MMI;
  var input = document.getElementById('serviceSearch');
  var clearBtn = document.getElementById('searchClear');
  var noResults = document.getElementById('noResults');
  var loading = document.getElementById('filterLoading');
  var grid = document.getElementById('serviceGrid');
  var chipBox = document.getElementById('filterScroll');
  var state = { category: 'all', query: '' };
  var timer = null;
  var items = [];
  var chips = [];

  // ---------- render from data ----------
  function render(cats, services) {
    var byCat = {};
    cats.forEach(function (c) { byCat[MMI.slug(c.slug)] = c; });

    var counts = {};
    services.forEach(function (s) { var k = MMI.slug(s.category); counts[k] = (counts[k] || 0) + 1; });

    chipBox.innerHTML =
      '<button class="filter-chip active" data-category="all" type="button"><i class="fas fa-th-large me-2"></i>All Services <span class="filter-count">' + services.length + '</span></button>' +
      cats.map(function (c) {
        var k = MMI.slug(c.slug);
        return '<button class="filter-chip" data-category="' + k + '" type="button"><i class="' + MMI.icon(c.icon) + ' me-2"></i>' + MMI.esc(c.name) +
          ' <span class="filter-count">' + (counts[k] || 0) + '</span></button>';
      }).join('');

    grid.innerHTML = services.map(function (s) {
      var k = MMI.slug(s.category);
      var cat = byCat[k];
      var catName = cat ? cat.name : 'Other';
      var img = MMI.img(s.image);
      var msg = encodeURIComponent('Hello MMI ENGINEERING, I am interested in your service: ' + s.name + '. Please provide more details.');
      var media = img
        ? '<img src="' + MMI.esc(img) + '" alt="' + MMI.esc(s.name) + '" loading="lazy">'
        : '<div class="service-card-img-placeholder"><i class="' + MMI.icon(cat && cat.icon ? cat.icon : 'fas fa-cogs') + '"></i></div>';
      return '<div class="col-xl-3 col-lg-4 col-md-6 service-item" data-category="' + k + '" data-name="' + MMI.esc(String(s.name).toLowerCase()) +
        '" data-desc="' + MMI.esc(String(s.description || '').toLowerCase()) + '" data-aos="fade-up">' +
        '<div class="service-card"><div class="service-card-img">' + media + '<span class="service-cat-badge">' + MMI.esc(catName) + '</span></div>' +
        '<div class="service-card-body"><h5 class="service-card-title">' + MMI.esc(s.name) + '</h5>' +
        '<p class="service-card-text">' + MMI.esc(s.description || '') + '</p>' +
        '<div class="service-card-footer"><a href="https://wa.me/' + MMI.waNumber + '?text=' + msg + '" target="_blank" rel="noopener" class="btn btn-book">' +
        '<i class="fab fa-whatsapp me-2"></i>Book Now</a></div></div></div></div>';
    }).join('');
  }

  // ---------- filtering ----------
  function collect() {
    items = Array.prototype.slice.call(document.querySelectorAll('.service-item'));
    chips = Array.prototype.slice.call(document.querySelectorAll('.filter-chip'));
    chips.forEach(function (c) {
      c.addEventListener('click', function () { setCategory(c.getAttribute('data-category')); });
    });
  }

  function apply() {
    var q = state.query.toLowerCase();
    var shown = 0;
    items.forEach(function (it) {
      var okCat = state.category === 'all' || it.getAttribute('data-category') === state.category;
      var hay = (it.getAttribute('data-name') || '') + ' ' + (it.getAttribute('data-desc') || '') + ' ' + (it.getAttribute('data-category') || '');
      var show = okCat && (!q || hay.indexOf(q) > -1);
      it.classList.toggle('hide', !show);
      if (show) shown++;
    });
    noResults.style.display = shown ? 'none' : 'block';
    grid.style.display = shown ? '' : 'none';
    clearBtn.classList.toggle('show', !!state.query);
  }

  function run() {
    loading.style.display = 'flex';
    clearTimeout(timer);
    timer = setTimeout(function () { loading.style.display = 'none'; apply(); }, 180);
  }

  function setCategory(cat) {
    state.category = cat;
    chips.forEach(function (c) { c.classList.toggle('active', c.getAttribute('data-category') === cat); });
    run();
  }

  function init() {
    collect();
    var params = new URLSearchParams(window.location.search);
    if (params.get('q')) { input.value = params.get('q'); state.query = params.get('q').trim(); }
    var cat = params.get('cat');
    if (cat && chips.some(function (c) { return c.getAttribute('data-category') === cat; })) setCategory(cat);
    else apply();
    MMI.refreshAnimations();
  }

  input.addEventListener('input', function () { state.query = input.value.trim(); run(); });
  clearBtn.addEventListener('click', function () { input.value = ''; state.query = ''; run(); input.focus(); });
  document.getElementById('resetFilters').addEventListener('click', function () {
    input.value = ''; state.query = ''; setCategory('all');
  });

  // Load data, fall back to the pre-rendered markup if the files can't be fetched
  Promise.all([MMI.json('categories'), MMI.json('services'), MMI.json('settings').catch(function () { return null; })])
    .then(function (r) {
      if (r[2] && r[2].whatsapp) MMI.waNumber = String(r[2].whatsapp).replace(/\D/g, '') || MMI.waNumber;
      render(MMI.items(r[0]), MMI.items(r[1]));
    })
    .catch(function () { /* keep static fallback */ })
    .then(init);

  // ---------- dark / light mode ----------
  var KEY = 'mmi-theme';
  function setTheme(t) {
    if (t === 'dark') document.body.setAttribute('data-theme', 'dark');
    else document.body.removeAttribute('data-theme');
  }
  try { setTheme(localStorage.getItem(KEY)); } catch (e) {}
  document.getElementById('themeToggle').addEventListener('click', function () {
    var dark = document.body.getAttribute('data-theme') === 'dark';
    setTheme(dark ? 'light' : 'dark');
    try { localStorage.setItem(KEY, dark ? 'light' : 'dark'); } catch (e) {}
  });
})();
