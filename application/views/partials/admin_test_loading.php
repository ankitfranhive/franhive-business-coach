<style>
    #admin-test-page-loader {
        position: fixed;
        inset: 0;
        z-index: 99999;
        background: rgba(255, 255, 255, 0.72);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        transition: opacity 0.2s ease;
    }
    #admin-test-page-loader.hidden {
        opacity: 0;
        pointer-events: none;
    }
    #admin-test-page-loader .spinner-border {
        width: 2.5rem;
        height: 2.5rem;
        color: #0f766e;
    }
    #admin-test-page-loader p {
        margin-top: 12px;
        color: #475569;
        font-size: 14px;
    }
    .btn.is-busy {
        pointer-events: none;
        opacity: 0.75;
    }
    div.dataTables_wrapper .dataTables_processing {
        z-index: 10;
        background: rgba(255, 255, 255, 0.92);
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        color: #0f766e;
        font-weight: 600;
    }
</style>
<div id="admin-test-page-loader" aria-live="polite">
    <div class="spinner-border" role="status"><span class="sr-only">Loading…</span></div>
    <p>Loading…</p>
</div>
<script>
(function () {
    function hidePageLoader() {
        var el = document.getElementById('admin-test-page-loader');
        if (el) el.classList.add('hidden');
    }
    if (document.readyState === 'complete') {
        hidePageLoader();
    } else {
        window.addEventListener('load', hidePageLoader);
        setTimeout(hidePageLoader, 12000);
    }

    $(document).on('submit', 'form', function () {
        var $f = $(this);
        if ($f.data('no-loader')) return;
        $f.find('button[type="submit"], input[type="submit"]').addClass('is-busy');
        var el = document.getElementById('admin-test-page-loader');
        if (el) {
            el.classList.remove('hidden');
            el.querySelector('p').textContent = 'Saving…';
        }
    });

    window.adminTestShowLoader = function (msg) {
        var el = document.getElementById('admin-test-page-loader');
        if (!el) return;
        el.querySelector('p').textContent = msg || 'Loading…';
        el.classList.remove('hidden');
    };
    window.adminTestHideLoader = hidePageLoader;
})();
</script>
