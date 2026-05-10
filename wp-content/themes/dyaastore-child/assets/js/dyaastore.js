/**
 * Dyaa Store — Frontend Script
 *
 * Fitur:
 * 1. Theme toggle (default DARK, persisted di localStorage), sinkron antara
 *    tombol topbar dan sidebar.
 * 2. Sidebar mobile open/close.
 * 3. Smooth scroll untuk anchor link.
 * 4. Animasi counter pada section stats.
 * 5. Countdown Flash Sale (per-tab session, reset tiap N jam).
 * 6. Live transaction toast — efek social proof (data dummy).
 *
 * Tugas Akhir Wahyu Akbar Pratama Siregar (NIM 0110122029) — STT-NF 2026.
 */

(function () {
    'use strict';

    // ---------- SVG icons (selaras dengan inc/icons.php) ----------
    const ICON_SUN =
        '<svg class="dyaa-icon" data-icon="sun" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" ' +
        'fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' +
        '<circle cx="12" cy="12" r="4"/><path d="M12 2v2"/><path d="M12 20v2"/><path d="m4.93 4.93 1.41 1.41"/>' +
        '<path d="m17.66 17.66 1.41 1.41"/><path d="M2 12h2"/><path d="M20 12h2"/>' +
        '<path d="m6.34 17.66-1.41 1.41"/><path d="m19.07 4.93-1.41 1.41"/></svg>';

    const ICON_MOON =
        '<svg class="dyaa-icon" data-icon="moon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" ' +
        'fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' +
        '<path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79Z"/></svg>';

    // ====================================================
    // 1. Theme Toggle — pill switch (default DARK, opt-in light)
    // ====================================================
    const STORAGE_KEY = 'dyaa-theme';
    const body = document.body;
    const themeButtons = [
        document.getElementById('dyaa-darkmode-btn'),
        document.getElementById('dyaa-darkmode-btn-side')
    ].filter(Boolean);

    const sideLabel = document.querySelector('[data-dyaa-theme-label]');

    const syncToggle = (isLight) => {
        themeButtons.forEach((b) => {
            b.classList.toggle('is-light', isLight);
            b.setAttribute('aria-checked', isLight ? 'true' : 'false');
            b.setAttribute('aria-label', isLight ? 'Aktifkan mode gelap' : 'Aktifkan mode terang');
            // Tetap support markup lama (kalau ada cache template) supaya tidak crash.
            const legacyIcon = b.querySelector('.dyaa-darkmode-icon');
            if (legacyIcon) legacyIcon.innerHTML = isLight ? ICON_MOON : ICON_SUN;
        });
        if (sideLabel) sideLabel.textContent = isLight ? 'Mode Gelap' : 'Mode Terang';
    };

    const savedTheme = localStorage.getItem(STORAGE_KEY);
    if (savedTheme === 'light') {
        body.classList.add('dyaa-light');
    } else {
        body.classList.remove('dyaa-light');
    }
    document.documentElement.classList.remove('dyaa-light-pre');
    syncToggle(body.classList.contains('dyaa-light'));

    themeButtons.forEach((b) => {
        b.addEventListener('click', function () {
            body.classList.toggle('dyaa-light');
            const isLight = body.classList.contains('dyaa-light');
            localStorage.setItem(STORAGE_KEY, isLight ? 'light' : 'dark');
            syncToggle(isLight);
        });
    });

    // ====================================================
    // 2. Sidebar mobile open/close
    // ====================================================
    const sidebarToggle = document.getElementById('dyaa-sidebar-toggle');
    const sidebar       = document.getElementById('dyaa-sidebar');
    const overlay       = document.getElementById('dyaa-sidebar-overlay');
    const closeSidebar  = () => {
        if (sidebar) sidebar.classList.remove('is-open');
        if (overlay) overlay.classList.remove('is-show');
        document.body.classList.remove('dyaa-sidebar-open');
    };
    const openSidebar = () => {
        if (sidebar) sidebar.classList.add('is-open');
        if (overlay) overlay.classList.add('is-show');
        document.body.classList.add('dyaa-sidebar-open');
    };
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', () => {
            if (sidebar && sidebar.classList.contains('is-open')) closeSidebar();
            else openSidebar();
        });
    }
    if (overlay) overlay.addEventListener('click', closeSidebar);
    document.querySelectorAll('.dyaa-sidebar .dyaa-side-link').forEach((a) => {
        a.addEventListener('click', () => {
            if (window.innerWidth <= 1024) closeSidebar();
        });
    });

    // ====================================================
    // 3. Smooth scroll untuk anchor link
    // ====================================================
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        const href = anchor.getAttribute('href');
        if (!href || href === '#' || href.length < 2) return;
        anchor.addEventListener('click', function (e) {
            const target = document.querySelector(href);
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });

    // ====================================================
    // 4. Animasi counter pada section stats
    //    Mendukung atribut data-count-to & data-count-suffix.
    // ====================================================
    const formatNumber = (value, decimals) => {
        const fixed = value.toFixed(decimals);
        const parts = fixed.split('.');
        parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        return parts.join(',');
    };

    const animateNumber = (el) => {
        const targetAttr = el.getAttribute('data-count-to');
        const suffix     = el.getAttribute('data-count-suffix') || '';
        const target     = targetAttr !== null
            ? parseFloat(targetAttr)
            : (function () {
                const m = (el.textContent.trim()).match(/^(\d+(?:[.,]\d+)?)(.*)/);
                return m ? parseFloat(m[1].replace(',', '.')) : NaN;
            })();

        if (isNaN(target)) return;

        const decimals = target % 1 !== 0 ? 1 : 0;
        const duration = 1600;
        const startTime = performance.now();

        const tick = (now) => {
            const progress = Math.min((now - startTime) / duration, 1);
            const ease = 1 - Math.pow(1 - progress, 3);
            const current = target * ease;
            el.textContent = formatNumber(current, decimals) + suffix;
            if (progress < 1) requestAnimationFrame(tick);
            else el.textContent = formatNumber(target, decimals) + suffix;
        };

        requestAnimationFrame(tick);
    };

    if ('IntersectionObserver' in window) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    animateNumber(entry.target);
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.4 });

        document.querySelectorAll('.dyaa-stat-number').forEach((el) => observer.observe(el));
    } else {
        document.querySelectorAll('.dyaa-stat-number').forEach(animateNumber);
    }

    // ====================================================
    // 5. Live Transaction Toast — efek "social proof"
    //    Catatan: dummy untuk efek visual, BUKAN data nyata.
    // ====================================================
    const liveBox = document.getElementById('dyaa-live-toast');
    if (liveBox) {
        const buyers = [
            'Andi Pratama', 'Maya Lestari', 'Rizky Anggara', 'Siti Nurhaliza',
            'Bagas Triwibowo', 'Dewi Kusuma', 'Ahmad Fadillah', 'Nadia Rahmawati',
            'Farhan Maulana', 'Kirana Wulandari', 'Lina Permata', 'Hendra Saputra'
        ];
        const products = [
            '100 Robux', '400 Robux', '800 Robux', '1.700 Robux', '4.500 Robux',
            'Bundle 2×800 Robux', 'Limited 2.200 Robux', '10.000 Robux'
        ];
        const times = [
            'baru saja', '1 menit lalu', '2 menit lalu', '4 menit lalu', '7 menit lalu'
        ];
        const pickRandom = (arr) => arr[Math.floor(Math.random() * arr.length)];
        const initial = (name) => name.split(' ').map((s) => s.charAt(0)).join('').slice(0, 2).toUpperCase();

        const elName = liveBox.querySelector('[data-live="name"]');
        const elProd = liveBox.querySelector('[data-live="product"]');
        const elTime = liveBox.querySelector('[data-live="time"]');
        const elAva  = liveBox.querySelector('[data-live="avatar"]');
        const elBox  = liveBox;
        let timer = null;

        const show = () => {
            if (!elName || !elProd) return;
            const buyer = pickRandom(buyers);
            elName.textContent = buyer;
            elProd.textContent = pickRandom(products);
            if (elTime) elTime.textContent = pickRandom(times);
            if (elAva) elAva.textContent = initial(buyer);
            elBox.classList.add('is-show');
            clearTimeout(timer);
            timer = setTimeout(() => elBox.classList.remove('is-show'), 5000);
        };

        const closeBtn = liveBox.querySelector('.dyaa-live-close');
        if (closeBtn) closeBtn.addEventListener('click', (e) => {
            e.preventDefault();
            elBox.classList.remove('is-show');
            elBox.dataset.dismissed = '1';
        });

        setTimeout(show, 3500);
        setInterval(() => {
            if (elBox.dataset.dismissed === '1') return;
            show();
        }, 14000);
    }

    // ====================================================
    // 6. Countdown Flash Sale
    // ====================================================
    document.querySelectorAll('.dyaa-countdown[data-dyaa-countdown]').forEach((wrap) => {
        const hours = parseInt(wrap.dataset.dyaaCountdown, 10) || 6;
        const KEY = 'dyaa-countdown-end-' + hours;
        const now = Date.now();
        let end = parseInt(localStorage.getItem(KEY) || '0', 10);

        if (!end || end < now) {
            end = now + hours * 3600 * 1000;
            localStorage.setItem(KEY, end.toString());
        }

        const hEl = wrap.querySelector('[data-cd="hours"]');
        const mEl = wrap.querySelector('[data-cd="minutes"]');
        const sEl = wrap.querySelector('[data-cd="seconds"]');
        if (!hEl || !mEl || !sEl) return;

        const pad = (n) => String(Math.max(0, n)).padStart(2, '0');

        const tick = () => {
            const diff = end - Date.now();
            if (diff <= 0) {
                end = Date.now() + hours * 3600 * 1000;
                localStorage.setItem(KEY, end.toString());
            }
            const total = Math.max(0, Math.floor(diff / 1000));
            const h = Math.floor(total / 3600);
            const m = Math.floor((total % 3600) / 60);
            const s = total % 60;
            hEl.textContent = pad(h);
            mEl.textContent = pad(m);
            sEl.textContent = pad(s);
        };

        tick();
        setInterval(tick, 1000);
    });

    // ====================================================
    // 7. Auth split-screen — tab Masuk / Daftar
    // ====================================================
    const authShell = document.querySelector('.dyaa-auth-shell');
    if (authShell) {
        const tabs   = authShell.querySelectorAll('[data-dyaa-tab]');
        const panes  = authShell.querySelectorAll('[data-dyaa-pane]');
        const tabBar = authShell.querySelector('.dyaa-auth-tabs');

        const setTab = (target) => {
            if (!target) return;
            tabs.forEach((t) => {
                const isActive = t.getAttribute('data-dyaa-tab') === target;
                t.classList.toggle('is-active', isActive);
                t.setAttribute('aria-selected', isActive ? 'true' : 'false');
            });
            panes.forEach((p) => {
                p.classList.toggle('is-active', p.getAttribute('data-dyaa-pane') === target);
            });
            if (tabBar) tabBar.setAttribute('data-active', target);

            // Sync URL ?action=register tanpa reload, supaya bisa di-share / di-bookmark.
            try {
                const url = new URL(window.location.href);
                if (target === 'register') url.searchParams.set('action', 'register');
                else url.searchParams.delete('action');
                window.history.replaceState({}, '', url);
            } catch (e) { /* noop */ }
        };

        tabs.forEach((t) => {
            t.addEventListener('click', (e) => {
                e.preventDefault();
                setTab(t.getAttribute('data-dyaa-tab'));
            });
        });

        // Inline switch link di bawah pane (Belum punya akun? Daftar Sekarang dst)
        authShell.querySelectorAll('[data-dyaa-tab-switch]').forEach((a) => {
            a.addEventListener('click', (e) => {
                e.preventDefault();
                setTab(a.getAttribute('data-dyaa-tab-switch'));
                // Scroll halus ke card kalau di mobile/stacked layout
                const card = authShell.querySelector('.dyaa-auth-card');
                if (card && window.innerWidth < 980) card.scrollIntoView({ behavior: 'smooth', block: 'start' });
            });
        });

        // Inisialisasi indicator sesuai initial tab dari server (PHP).
        const initialTab = authShell.getAttribute('data-initial-tab') || 'login';
        if (tabBar) tabBar.setAttribute('data-active', initialTab);
    }

})();
