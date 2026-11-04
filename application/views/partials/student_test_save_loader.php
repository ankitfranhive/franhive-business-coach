<div id="student-test-save-loader" aria-live="polite" aria-busy="true" style="display:none;">
    <div class="student-test-save-loader-panel">
        <div class="student-test-save-spinner" role="status" aria-hidden="true"></div>
        <p id="student-test-save-loader-msg">Saving your progress…</p>
        <p class="student-test-save-loader-hint">Please wait — do not close this page.</p>
    </div>
</div>
<style>
    #student-test-save-loader {
        position: fixed;
        inset: 0;
        z-index: 100000;
        background: rgba(15, 23, 42, 0.55);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }
    .student-test-save-loader-panel {
        background: #0f766e;
        color: #fff;
        border-radius: 14px;
        padding: 28px 32px;
        text-align: center;
        max-width: 360px;
        width: 100%;
        box-shadow: 0 16px 48px rgba(0, 0, 0, 0.25);
    }
    .student-test-save-spinner {
        width: 44px;
        height: 44px;
        margin: 0 auto 16px;
        border: 3px solid rgba(255, 255, 255, 0.35);
        border-top-color: #fff;
        border-radius: 50%;
        animation: student-test-save-spin 0.75s linear infinite;
    }
    @keyframes student-test-save-spin {
        to { transform: rotate(360deg); }
    }
    #student-test-save-loader-msg {
        font-size: 1.05rem;
        font-weight: 600;
        margin: 0 0 8px;
    }
    .student-test-save-loader-hint {
        font-size: 0.85rem;
        opacity: 0.9;
        margin: 0;
    }
</style>
<script>
(function () {
    window.showTestSaveLoader = function (message) {
        var el = document.getElementById('student-test-save-loader');
        var msg = document.getElementById('student-test-save-loader-msg');
        if (!el) return;
        if (msg) msg.textContent = message || 'Saving your progress…';
        el.style.display = 'flex';
    };
    window.hideTestSaveLoader = function () {
        var el = document.getElementById('student-test-save-loader');
        if (el) el.style.display = 'none';
        var btn = document.getElementById('saveExitBtn');
        if (btn) {
            btn.disabled = false;
            if (btn.getAttribute('data-label')) {
                btn.textContent = btn.getAttribute('data-label');
            }
        }
    };
})();
</script>
