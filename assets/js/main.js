/* kiuixshop - site scripts */
(function () {
    'use strict';

    var base = (window.KIUIX && window.KIUIX.base) || '';

    // ---- AOS animations ----
    if (window.AOS) { AOS.init({ duration: 700, once: true, offset: 60 }); }

    // ---- Dark / light theme ----
    var toggle = document.getElementById('themeToggle');
    if (toggle) {
        toggle.addEventListener('click', function () {
            var root = document.documentElement;
            var next = root.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
            root.setAttribute('data-theme', next);
            try { localStorage.setItem('kiuix-theme', next); } catch (e) {}
        });
    }

    // ---- Home hero search -> products page ----
    var heroSearch = document.getElementById('heroSearch');
    if (heroSearch) {
        var go = function () {
            var q = heroSearch.value.trim();
            window.location.href = base + '/products.php' + (q ? '?q=' + encodeURIComponent(q) : '');
        };
        heroSearch.addEventListener('keydown', function (e) { if (e.key === 'Enter') { e.preventDefault(); go(); } });
        var heroBtn = document.querySelector('.hero-search-btn');
        if (heroBtn) { heroBtn.addEventListener('click', go); }
    }

    // ---- Products page: search + category filter ----
    var grid = document.getElementById('productGrid');
    if (grid) {
        var items = Array.prototype.slice.call(grid.querySelectorAll('.product-item'));
        var input = document.getElementById('productSearch');
        var clearBtn = document.getElementById('searchClear');
        var wrapper = input ? input.closest('.search-wrapper') : null;
        var noResults = document.getElementById('noResults');
        var loading = document.getElementById('filterLoading');
        var chips = Array.prototype.slice.call(document.querySelectorAll('.filter-chip'));
        var activeCategory = 'all';

        function apply() {
            var q = input ? input.value.trim().toLowerCase() : '';
            var visible = 0;
            items.forEach(function (el) {
                var matchCat = activeCategory === 'all' || el.getAttribute('data-category') === activeCategory;
                var matchText = !q || el.getAttribute('data-name').indexOf(q) !== -1 || el.getAttribute('data-desc').indexOf(q) !== -1;
                var show = matchCat && matchText;
                el.style.display = show ? '' : 'none';
                if (show) { visible++; }
            });
            if (noResults) { noResults.style.display = visible === 0 ? '' : 'none'; }
            if (wrapper) { wrapper.classList.toggle('has-value', q.length > 0); }
        }

        window.filterByCategory = function (slug) {
            activeCategory = slug;
            chips.forEach(function (c) { c.classList.toggle('active', c.getAttribute('data-category') === slug); });
            if (loading) {
                loading.style.display = '';
                setTimeout(function () { loading.style.display = 'none'; apply(); }, 180);
            } else { apply(); }
        };

        window.resetFilters = function () {
            if (input) { input.value = ''; }
            window.filterByCategory('all');
        };

        if (input) { input.addEventListener('input', apply); }
        if (clearBtn) { clearBtn.addEventListener('click', function () { input.value = ''; input.focus(); apply(); }); }

        // Pre-filter from ?q= and ?category= in the URL
        var params = new URLSearchParams(window.location.search);
        if (params.get('q') && input) { input.value = params.get('q'); }
        var cat = params.get('category');
        if (cat && chips.some(function (c) { return c.getAttribute('data-category') === cat; })) { activeCategory = cat; chips.forEach(function (c) { c.classList.toggle('active', c.getAttribute('data-category') === cat); }); }
        apply();
    }
})();
