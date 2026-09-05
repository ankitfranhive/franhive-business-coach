<div class="cm-contact-toolbar">
    <input type="search" id="contactSearch" class="form-control" placeholder="Search name, email, or phone">
    <select id="contactPageSize" class="custom-select">
        <option value="10">10 per page</option>
        <option value="25" selected>25 per page</option>
        <option value="50">50 per page</option>
        <option value="100">100 per page</option>
    </select>
    <span class="cm-contact-meta" id="contactResultMeta"></span>
    <span class="cm-contact-meta" id="contactSelectedMeta"></span>
</div>
<div class="cm-contact-empty" id="contactEmptyState">No matching people. Try a different search.</div>
<div class="cm-contact-pager" id="contactPager"></div>
<script>
(function () {
    var contactPage = 1;

    function activeContactWrap() {
        var audience = typeof currentAudience === 'function' ? currentAudience() : '';
        var ids = {
            Lead: 'leadsContactsWrap',
            Client: 'clientsContactsWrap',
            User: 'crmUsersWrap'
        };
        return document.getElementById(ids[audience] || '');
    }

    function visibleRows(wrap) {
        return Array.prototype.slice.call(wrap.querySelectorAll('tbody tr')).filter(function (tr) {
            return tr.style.display !== 'none';
        });
    }

    function updateSelectedMeta(wrap) {
        var selectedMeta = document.getElementById('contactSelectedMeta');
        if (!selectedMeta || !wrap) {
            if (selectedMeta) selectedMeta.textContent = '';
            return;
        }
        var count = wrap.querySelectorAll('input[name="CONTACT_IDS[]"]:checked:not(:disabled)').length;
        selectedMeta.textContent = count ? (count + ' selected') : '';
    }

    function renderPager(matchedCount, pageSize) {
        var pager = document.getElementById('contactPager');
        if (!pager) return;
        var totalPages = Math.max(1, Math.ceil(matchedCount / pageSize) || 1);
        if (contactPage > totalPages) contactPage = totalPages;
        if (matchedCount === 0) {
            pager.innerHTML = '';
            return;
        }
        var html = '<button type="button" class="btn btn-sm btn-outline-secondary" data-page="prev" ' + (contactPage <= 1 ? 'disabled' : '') + '>Prev</button>';
        var start = Math.max(1, contactPage - 2);
        var end = Math.min(totalPages, start + 4);
        start = Math.max(1, end - 4);
        for (var i = start; i <= end; i++) {
            html += '<button type="button" class="btn btn-sm btn-outline-secondary cm-page-btn' + (i === contactPage ? ' active' : '') + '" data-page="' + i + '">' + i + '</button>';
        }
        html += '<button type="button" class="btn btn-sm btn-outline-secondary" data-page="next" ' + (contactPage >= totalPages ? 'disabled' : '') + '>Next</button>';
        pager.innerHTML = html;
    }

    window.refreshContactList = function (resetPage) {
        if (resetPage) contactPage = 1;
        var wrap = activeContactWrap();
        var search = document.getElementById('contactSearch');
        var pageSizeEl = document.getElementById('contactPageSize');
        var meta = document.getElementById('contactResultMeta');
        var empty = document.getElementById('contactEmptyState');
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
            var text = (tr.textContent || '').toLowerCase().replace(/\s+/g, ' ');
            if (!query || text.indexOf(query) !== -1) {
                matched.push(tr);
            }
        });
        var totalPages = Math.max(1, Math.ceil(matched.length / pageSize) || 1);
        if (contactPage > totalPages) contactPage = totalPages;
        var start = (contactPage - 1) * pageSize;
        var end = start + pageSize;
        rows.forEach(function (tr) { tr.style.display = 'none'; });
        matched.forEach(function (tr, index) {
            tr.style.display = (index >= start && index < end) ? '' : 'none';
        });
        if (meta) {
            if (!matched.length) {
                meta.textContent = query ? '0 matches' : '0 people';
            } else {
                var shownStart = start + 1;
                var shownEnd = Math.min(end, matched.length);
                meta.textContent = 'Showing ' + shownStart + '-' + shownEnd + ' of ' + matched.length + (query ? ' matches' : '');
            }
        }
        if (empty) empty.style.display = matched.length ? 'none' : 'block';
        renderPager(matched.length, pageSize);
        updateSelectedMeta(wrap);
        var headerBox = wrap.querySelector('thead .contact-select-page');
        if (headerBox) {
            var pageRows = visibleRows(wrap);
            var pageBoxes = pageRows.map(function (tr) { return tr.querySelector('input[name="CONTACT_IDS[]"]'); }).filter(Boolean);
            headerBox.checked = pageBoxes.length > 0 && pageBoxes.every(function (cb) { return cb.checked; });
        }
    };

    document.addEventListener('DOMContentLoaded', function () {
        if (window.__contactFiltersBound) return;
        window.__contactFiltersBound = true;

        var orig = window.setAudienceTables;
        if (typeof orig === 'function') {
            window.setAudienceTables = function () {
                orig();
                var search = document.getElementById('contactSearch');
                if (search) search.value = '';
                window.refreshContactList(true);
            };
        }

        var search = document.getElementById('contactSearch');
        var pageSize = document.getElementById('contactPageSize');
        var pager = document.getElementById('contactPager');
        if (search) {
            search.addEventListener('input', function () { window.refreshContactList(true); });
            search.addEventListener('keydown', function (event) {
                if (event.key === 'Enter') event.preventDefault();
            });
        }
        if (pageSize) {
            pageSize.addEventListener('change', function () { window.refreshContactList(true); });
        }
        if (pager) {
            pager.addEventListener('click', function (event) {
                var btn = event.target.closest('button[data-page]');
                if (!btn || btn.disabled) return;
                var action = btn.getAttribute('data-page');
                if (action === 'prev') contactPage -= 1;
                else if (action === 'next') contactPage += 1;
                else contactPage = parseInt(action, 10) || 1;
                if (contactPage < 1) contactPage = 1;
                window.refreshContactList(false);
            });
        }
        var screen3 = document.getElementById('screen3');
        if (!screen3) return;
        screen3.addEventListener('change', function (event) {
            var target = event.target;
            if (target.classList.contains('contact-select-page')) {
                var wrap = activeContactWrap();
                if (!wrap) return;
                visibleRows(wrap).forEach(function (tr) {
                    var cb = tr.querySelector('input[name="CONTACT_IDS[]"]');
                    if (cb && !cb.disabled) cb.checked = target.checked;
                });
            }
            updateSelectedMeta(activeContactWrap());
        });
    });
})();
</script>
