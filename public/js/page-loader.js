/**
 * page-loader.js
 * Full-screen overlay loader for page-to-page navigation.
 * Works on both admin and customer layouts.
 */
(function () {
    var overlay = document.getElementById('pageLoader');
    if (!overlay) return;

    // ─── Hide overlay when the new page is ready ───
    function hideLoader() {
        overlay.classList.add('loader-hidden');
        // Remove from flow after fade-out transition (400ms)
        setTimeout(function () {
            overlay.style.display = 'none';
        }, 700);
    }

    // ─── Show overlay ───
    function showLoader() {
        overlay.style.display = 'flex';
        // Force reflow then remove hidden class to trigger fade-in
        // eslint-disable-next-line no-unused-expressions
        overlay.offsetHeight;
        overlay.classList.remove('loader-hidden');
    }

    // Hide on initial page load
    document.addEventListener('DOMContentLoaded', function () {
        hideLoader();
    });

    // Hide also on back/forward (bfcache restore)
    window.addEventListener('pageshow', function (e) {
        if (e.persisted) hideLoader();
    });

    // ─── Show on link clicks that cause full navigation ───
    document.addEventListener('click', function (e) {
        var target = e.target.closest('a');
        if (!target) return;

        var href = target.getAttribute('href');
        if (!href) return;

        // Skip: empty, hash-only, javascript:, external, _blank, ajax-trigger
        var skip =
            href === '#' ||
            href.startsWith('#') ||
            href.startsWith('javascript') ||
            href.startsWith('mailto:') ||
            href.startsWith('tel:') ||
            target.target === '_blank' ||
            target.hasAttribute('data-no-loader') ||
            e.ctrlKey || e.metaKey || e.shiftKey;

        if (skip) return;

        // Skip if it's an external link (different origin)
        try {
            var url = new URL(href, window.location.origin);
            if (url.origin !== window.location.origin) return;
        } catch (_) {
            return;
        }

        showLoader();
    });

    // ─── Show on form submits (login, search, filters, etc.) ───
    document.addEventListener('submit', function (e) {
        var form = e.target;
        // Skip forms marked as AJAX / no-loader
        if (form.hasAttribute('data-no-loader')) return;
        // Skip forms with method override DELETE (admin destroy) — they reload fast
        var methodInput = form.querySelector('input[name="_method"]');
        if (methodInput && methodInput.value.toUpperCase() === 'DELETE') return;

        showLoader();
    });
})();
