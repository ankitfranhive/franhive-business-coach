<div class="cm-contact-toolbar">
    <input type="search" id="templateSearch" class="form-control" placeholder="Search template name or subject">
    <select id="templatePageSize" class="custom-select">
        <option value="10">10 per page</option>
        <option value="25" selected>25 per page</option>
        <option value="50">50 per page</option>
        <option value="100">100 per page</option>
    </select>
    <span class="cm-contact-meta" id="templateResultMeta"></span>
    <span class="cm-contact-meta" id="templateSelectedMeta"></span>
</div>
<div class="cm-contact-empty" id="templateEmptyState">No matching templates. Try a different search.</div>
<div class="cm-contact-pager" id="templatePager"></div>
<script>
(function () {
    var templatePage = 1;

    function templateWrap() {
        return document.getElementById('templatesTableWrap');
    }

    function visibleRows(wrap) {
        return Array.prototype.slice.call(wrap.querySelectorAll('tbody tr')).filter(function (tr) {
            return tr.style.display !== 'none';
        });
    }

    function rowSearchText(tr) {
        var parts = Array.prototype.slice.call(tr.querySelectorAll('[data-search]')).map(function (el) {
            return el.getAttribute('data-search') || '';
        });
        if (!parts.length) {
            return (tr.textContent || '').toLowerCase().replace(/\s+/g, ' ');
        }
        return parts.join(' ').toLowerCase().replace(/\s+/g, ' ');
    }

    function updateSelectedMeta(wrap) {
        var selectedMeta = document.getElementById('templateSelectedMeta');
        if (!selectedMeta || !wrap) {
            if (selectedMeta) selectedMeta.textContent = '';
            return;
        }
        var count = wrap.querySelectorAll('input[name="TEMPLATE_IDS[]"]:checked').length;
        selectedMeta.textContent = count ? (count + ' selected') : '';
    }

    function renderPager(matchedCount, pageSize) {
        var pager = document.getElementById('templatePager');
        if (!pager) return;
        var totalPages = Math.max(1, Math.ceil(matchedCount / pageSize) || 1);
        if (templatePage > totalPages) templatePage = totalPages;
        if (matchedCount === 0) {
            pager.innerHTML = '';
            return;
        }
        var html = '<button type="button" class="btn btn-sm btn-outline-secondary" data-page="prev" ' + (templatePage <= 1 ? 'disabled' : '') + '>Prev</button>';
        var start = Math.max(1, templatePage - 2);
        var end = Math.min(totalPages, start + 4);
        start = Math.max(1, end - 4);
        for (var i = start; i <= end; i++) {
            html += '<button type="button" class="btn btn-sm btn-outline-secondary cm-page-btn' + (i === templatePage ? ' active' : '') + '" data-page="' + i + '">' + i + '</button>';
        }
        html += '<button type="button" class="btn btn-sm btn-outline-secondary" data-page="next" ' + (templatePage >= totalPages ? 'disabled' : '') + '>Next</button>';
        pager.innerHTML = html;
    }

    window.refreshTemplateList = function (resetPage) {
        if (resetPage) templatePage = 1;
        var wrap = templateWrap();
        var search = document.getElementById('templateSearch');
        var pageSizeEl = document.getElementById('templatePageSize');
        var meta = document.getElementById('templateResultMeta');
        var empty = document.getElementById('templateEmptyState');
        if (!wrap) {
            if (meta) meta.textContent = '';
            if (empty) empty.style.display = 'none';
            renderPager(0, 25);
            updateSelectedMeta(null);
            return;
        }
        var query = ((search && search.value) || '').toLowerCase().trim();
        var pageSize = parseInt(pageSizeEl && pageSizeEl.value ? pageSizeEl.value : '25', 10) || 25;
        var rows = Array.prototype.slice.call(wrap.querySelectorAll('tbody tr'));
        var matched = [];
        rows.forEach(function (tr) {
            if (!query || rowSearchText(tr).indexOf(query) !== -1) {
                matched.push(tr);
            }
        });
        var totalPages = Math.max(1, Math.ceil(matched.length / pageSize) || 1);
        if (templatePage > totalPages) templatePage = totalPages;
        var start = (templatePage - 1) * pageSize;
        var end = start + pageSize;
        rows.forEach(function (tr) { tr.style.display = 'none'; });
        matched.forEach(function (tr, index) {
            tr.style.display = (index >= start && index < end) ? '' : 'none';
        });
        if (meta) {
            if (!matched.length) {
                meta.textContent = query ? '0 matches' : '0 templates';
            } else {
                meta.textContent = 'Showing ' + (start + 1) + '-' + Math.min(end, matched.length) + ' of ' + matched.length + (query ? ' matches' : '');
            }
        }
        if (empty) empty.style.display = matched.length ? 'none' : 'block';
        renderPager(matched.length, pageSize);
        updateSelectedMeta(wrap);
        var headerBox = wrap.querySelector('thead .template-select-page');
        if (headerBox) {
            var pageBoxes = visibleRows(wrap).map(function (tr) {
                return tr.querySelector('input[name="TEMPLATE_IDS[]"]');
            }).filter(Boolean);
            headerBox.checked = pageBoxes.length > 0 && pageBoxes.every(function (cb) { return cb.checked; });
        }
    };

    document.addEventListener('DOMContentLoaded', function () {
        if (window.__templateFiltersBound) return;
        window.__templateFiltersBound = true;

        var search = document.getElementById('templateSearch');
        var pageSize = document.getElementById('templatePageSize');
        var pager = document.getElementById('templatePager');
        if (search) {
            search.addEventListener('input', function () { window.refreshTemplateList(true); });
            search.addEventListener('keydown', function (event) {
                if (event.key === 'Enter') event.preventDefault();
            });
        }
        if (pageSize) {
            pageSize.addEventListener('change', function () { window.refreshTemplateList(true); });
        }
        if (pager) {
            pager.addEventListener('click', function (event) {
                var btn = event.target.closest('button[data-page]');
                if (!btn || btn.disabled) return;
                var action = btn.getAttribute('data-page');
                if (action === 'prev') templatePage -= 1;
                else if (action === 'next') templatePage += 1;
                else templatePage = parseInt(action, 10) || 1;
                if (templatePage < 1) templatePage = 1;
                window.refreshTemplateList(false);
            });
        }
        var screen2 = document.getElementById('screen2');
        if (screen2) {
            screen2.addEventListener('change', function (event) {
                var target = event.target;
                if (target.classList.contains('template-select-page')) {
                    var wrap = templateWrap();
                    if (!wrap) return;
                    visibleRows(wrap).forEach(function (tr) {
                        var cb = tr.querySelector('input[name="TEMPLATE_IDS[]"]');
                        if (cb) cb.checked = target.checked;
                    });
                }
                updateSelectedMeta(templateWrap());
                var headerBox = templateWrap() && templateWrap().querySelector('thead .template-select-page');
                if (headerBox && event.target && event.target.name === 'TEMPLATE_IDS[]') {
                    var pageBoxes = visibleRows(templateWrap()).map(function (tr) {
                        return tr.querySelector('input[name="TEMPLATE_IDS[]"]');
                    }).filter(Boolean);
                    headerBox.checked = pageBoxes.length > 0 && pageBoxes.every(function (cb) { return cb.checked; });
                }
            });
        }
        window.refreshTemplateList(true);
    });
})();
</script>
