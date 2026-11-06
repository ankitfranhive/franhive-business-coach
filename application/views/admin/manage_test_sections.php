<!DOCTYPE html>
<html>
<?php $this->load->view('includes/header'); ?>
<?php $this->load->view('partials/admin_test_loading'); ?>
<?php
$test_id = (int) ($test_id ?? 0);
$testName = $test['TEST_NAME'] ?? ('Test #' . $test_id);
$sectionsList = $sections ?? [];
$assignmentCount = (int) ($assignment_count ?? 0);
?>

<div class="mobile-menu-overlay"></div>
<div class="main-container">
    <div class="pd-ltr-20 xs-pd-20-10">
        <div class="page-header">
            <div class="row">
                <div class="col-md-8">
                    <h4>Sections — <?= htmlspecialchars($testName) ?></h4>
                    <p class="text-muted mb-0">Section 1 → Value 2, Section 2 → Value 3, etc. Assign by question group to keep counts exact. Live test order is oldest question first (ID ascending) within each section.</p>
                </div>
                <div class="col-md-4 text-right">
                    <a class="btn btn-warning" href="<?= base_url('edit-test/' . $test_id) ?>">Edit test</a>
                    <a class="btn btn-outline-secondary" href="<?= base_url('add-question-test/' . $test_id) ?>">Add questions</a>
                </div>
            </div>
        </div>

        <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success"><?= html_escape($this->session->flashdata('success')) ?></div>
        <?php endif; ?>

        <div class="card-box mb-30">
            <form method="post" action="<?= base_url('save-test-section-config') ?>" id="sectionsForm">
                <input type="hidden" name="test_id" value="<?= $test_id ?>">
                <input type="hidden" name="sections_json" id="sections_json">
                <input type="hidden" name="assignments_json" id="assignments_json">

                <div class="pd-20">
                    <h5 class="mb-3">Sections</h5>
                    <div id="sectionsEditor"></div>
                    <button type="button" class="btn btn-sm btn-outline-primary mt-2" id="addSectionRow">+ Add section</button>
                </div>

                <div class="pd-20 border-top">
                    <h5 class="mb-1">Assign questions to sections</h5>
                    <p class="text-muted small mb-2" id="assignmentsMeta">
                        <?= $assignmentCount > 0
                            ? htmlspecialchars((string) $assignmentCount) . ' question(s) — loading list…'
                            : 'No questions mapped yet. Use Add questions.' ?>
                    </p>
                    <div id="sectionCountsBar" class="mb-3"></div>
                    <div id="assignmentGroups">
                        <?php if ($assignmentCount <= 0): ?>
                            <p class="text-muted">No questions mapped yet. Use Add questions.</p>
                        <?php else: ?>
                            <p class="text-muted">Loading questions…</p>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="pd-20">
                    <button type="submit" class="btn btn-success" id="saveSectionsBtn">Save sections &amp; assignments</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $this->load->view('includes/footer'); ?>
<script>
(function () {
    var assignmentsUrl = <?= json_encode(base_url('test-section-assignments-json/' . $test_id)) ?>;
    var initialSections = <?= json_encode(array_map(function ($s) {
        return [
            'id' => (int) ($s['SECTION_ID'] ?? 0),
            'name' => $s['SECTION_NAME'] ?? '',
        ];
    }, $sectionsList)) ?>;

    var container = document.getElementById('sectionsEditor');
    var assignmentRows = [];
    var assignmentsLoaded = false;

    function escapeHtml(s) {
        return String(s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/"/g, '&quot;');
    }

    function sectionOptionsHtml(selectedIndex) {
        var html = '<option value="">— Unassigned —</option>';
        container.querySelectorAll('.section-name').forEach(function (inp, idx) {
            var name = inp.value.trim() || ('Section ' + (idx + 1));
            var sel = selectedIndex !== '' && String(selectedIndex) === String(idx) ? ' selected' : '';
            html += '<option value="' + idx + '"' + sel + '>' + escapeHtml(name) + ' (Value ' + (idx + 2) + ')</option>';
        });
        return html;
    }

    function groupedRows() {
        var groups = {};
        var order = [];
        assignmentRows.forEach(function (row) {
            var key = row.question_group || '(No group)';
            if (!groups[key]) {
                groups[key] = [];
                order.push(key);
            }
            groups[key].push(row);
        });
        order.sort(function (a, b) { return a.localeCompare(b); });
        order.forEach(function (key) {
            groups[key].sort(function (a, b) {
                return (a.question_id || 0) - (b.question_id || 0);
            });
        });
        return { order: order, groups: groups };
    }

    function renderCounts() {
        var bar = document.getElementById('sectionCountsBar');
        if (!bar) return;
        var names = [];
        container.querySelectorAll('.section-name').forEach(function (inp, idx) {
            names[idx] = inp.value.trim() || ('Section ' + (idx + 1));
        });
        var counts = {};
        var unassigned = 0;
        assignmentRows.forEach(function (row) {
            if (row.section_index === '' || row.section_index === null || row.section_index === undefined) {
                unassigned++;
                return;
            }
            var idx = String(row.section_index);
            counts[idx] = (counts[idx] || 0) + 1;
        });
        var parts = names.map(function (name, idx) {
            return '<span class="badge badge-info mr-2 mb-1">' + escapeHtml(name) + ': ' + (counts[String(idx)] || 0) + '</span>';
        });
        if (unassigned) {
            parts.push('<span class="badge badge-secondary mr-2 mb-1">Unassigned: ' + unassigned + '</span>');
        }
        bar.innerHTML = parts.join(' ');
    }

    function renderAssignmentRows() {
        var wrap = document.getElementById('assignmentGroups');
        wrap.innerHTML = '';
        if (!assignmentRows.length) {
            wrap.innerHTML = '<p class="text-muted">No questions mapped yet. Use Add questions.</p>';
            renderCounts();
            return;
        }

        var grouped = groupedRows();
        grouped.order.forEach(function (groupName) {
            var rows = grouped.groups[groupName];
            var card = document.createElement('div');
            card.className = 'border rounded mb-3';
            card.innerHTML =
                '<div class="d-flex flex-wrap align-items-center justify-content-between p-2 bg-light">' +
                    '<strong>' + escapeHtml(groupName) + ' <span class="text-muted font-weight-normal">(' + rows.length + ' questions)</span></strong>' +
                    '<div class="d-flex align-items-center mt-1 mt-md-0">' +
                        '<span class="small text-muted mr-2">Assign all in this group:</span>' +
                        '<select class="form-control form-control-sm assign-group" data-group="' + escapeHtml(groupName) + '" style="min-width:220px">' +
                            sectionOptionsHtml('') +
                        '</select>' +
                    '</div>' +
                '</div>' +
                '<div class="table-responsive"><table class="table table-sm table-bordered mb-0">' +
                    '<thead><tr><th>ID</th><th>Question</th><th style="min-width:200px">Section</th></tr></thead>' +
                    '<tbody></tbody>' +
                '</table></div>';
            var tbody = card.querySelector('tbody');
            rows.forEach(function (row) {
                var tr = document.createElement('tr');
                tr.setAttribute('data-mapping-id', String(row.mapping_id));
                tr.innerHTML =
                    '<td>' + (row.question_id || '') + '</td>' +
                    '<td>' + escapeHtml(row.question_label || '') + '</td>' +
                    '<td><select class="form-control form-control-sm assign-section">' +
                    sectionOptionsHtml(row.section_index !== undefined && row.section_index !== null ? row.section_index : '') +
                    '</select></td>';
                tbody.appendChild(tr);
            });
            wrap.appendChild(card);
        });
        renderCounts();
    }

    function rebuildAssignmentDropdowns() {
        document.querySelectorAll('#assignmentGroups .assign-section, #assignmentGroups .assign-group').forEach(function (sel) {
            var mappingId = sel.classList.contains('assign-section')
                ? parseInt(sel.closest('tr').getAttribute('data-mapping-id'), 10)
                : null;
            var row = mappingId ? assignmentRows.find(function (r) { return r.mapping_id === mappingId; }) : null;
            var prev = row ? row.section_index : sel.value;
            var maxIdx = container.querySelectorAll('.section-name').length;
            sel.innerHTML = sectionOptionsHtml('');
            if (prev !== '' && prev !== null && prev !== undefined && parseInt(prev, 10) >= 0 && parseInt(prev, 10) < maxIdx) {
                sel.value = String(prev);
            }
        });
        renderCounts();
    }

    document.getElementById('assignmentGroups').addEventListener('change', function (e) {
        var sel = e.target;
        if (sel.classList.contains('assign-section')) {
            var mappingId = parseInt(sel.closest('tr').getAttribute('data-mapping-id'), 10);
            var row = assignmentRows.find(function (r) { return r.mapping_id === mappingId; });
            if (row) row.section_index = sel.value;
            renderCounts();
        }
        if (sel.classList.contains('assign-group')) {
            var groupName = sel.getAttribute('data-group');
            assignmentRows.forEach(function (row) {
                var key = row.question_group || '(No group)';
                if (key === groupName) {
                    row.section_index = sel.value;
                }
            });
            renderAssignmentRows();
        }
    });

    function loadAssignments() {
        if (<?= (int) $assignmentCount ?> <= 0) {
            assignmentsLoaded = true;
            if (window.adminTestHideLoader) adminTestHideLoader();
            return;
        }
        if (window.adminTestShowLoader) adminTestShowLoader('Loading questions…');
        fetch(assignmentsUrl, { credentials: 'same-origin', headers: { Accept: 'application/json' } })
            .then(function (res) {
                if (!res.ok) throw new Error('HTTP ' + res.status);
                return res.json();
            })
            .then(function (data) {
                if (!data || !data.ok) throw new Error((data && data.error) || 'Failed to load questions');
                assignmentRows = (data.rows || []).map(function (r) {
                    return {
                        mapping_id: r.mapping_id,
                        question_id: r.question_id,
                        question_label: r.question_label,
                        question_group: r.question_group,
                        section_index: r.section_index !== undefined && r.section_index !== null && r.section_index !== ''
                            ? String(r.section_index)
                            : ''
                    };
                });
                var meta = document.getElementById('assignmentsMeta');
                if (meta) {
                    meta.textContent = assignmentRows.length + ' question(s), grouped by question group. Use “Assign all in this group” then Save.';
                }
                renderAssignmentRows();
                assignmentsLoaded = true;
                if (window.adminTestHideLoader) adminTestHideLoader();
            })
            .catch(function (err) {
                document.getElementById('assignmentGroups').innerHTML =
                    '<p class="text-danger">Could not load questions (' + escapeHtml(err.message || 'error') + '). Refresh the page.</p>';
                if (window.adminTestHideLoader) adminTestHideLoader();
            });
    }

    function renderSections() {
        container.innerHTML = '';
        initialSections.forEach(function (sec, idx) {
            var row = document.createElement('div');
            row.className = 'form-row align-items-center mb-2';
            row.innerHTML =
                '<div class="col-md-1 text-muted">#' + (idx + 1) + '</div>' +
                '<div class="col-md-8">' +
                '<input type="text" class="form-control section-name" data-id="' + (sec.id || '') + '" value="' + (sec.name || '').replace(/"/g, '&quot;') + '" placeholder="Section name (e.g. Segment 1)">' +
                '</div>' +
                '<div class="col-md-2"><small class="text-muted">Value ' + (idx + 2) + '</small></div>' +
                '<div class="col-md-1"><button type="button" class="btn btn-sm btn-outline-danger rm-sec">&times;</button></div>';
            container.appendChild(row);
        });
        bindSectionRows();
        rebuildAssignmentDropdowns();
    }

    function bindSectionRows() {
        container.querySelectorAll('.rm-sec').forEach(function (btn) {
            btn.onclick = function () {
                var row = btn.closest('.form-row');
                var i = Array.prototype.indexOf.call(container.children, row);
                if (i >= 0) initialSections.splice(i, 1);
                renderSections();
                renderAssignmentRows();
            };
        });
        container.querySelectorAll('.section-name').forEach(function (inp) {
            inp.oninput = function () {
                var i = Array.prototype.indexOf.call(container.querySelectorAll('.section-name'), inp);
                if (i >= 0 && initialSections[i]) initialSections[i].name = inp.value;
                rebuildAssignmentDropdowns();
            };
        });
    }

    document.getElementById('addSectionRow').onclick = function () {
        initialSections.push({ id: 0, name: '' });
        renderSections();
        renderAssignmentRows();
    };

    document.getElementById('sectionsForm').onsubmit = function () {
        if (!assignmentsLoaded && <?= (int) $assignmentCount ?> > 0) {
            alert('Questions are still loading. Please wait a moment before saving.');
            return false;
        }
        var secs = [];
        container.querySelectorAll('.section-name').forEach(function (inp) {
            secs.push({ id: parseInt(inp.getAttribute('data-id'), 10) || 0, name: inp.value.trim() });
        });
        document.getElementById('sections_json').value = JSON.stringify(secs);

        var assigns = assignmentRows.map(function (row) {
            return {
                mapping_id: row.mapping_id,
                section_index: row.section_index !== undefined && row.section_index !== null ? row.section_index : ''
            };
        });
        document.getElementById('assignments_json').value = JSON.stringify(assigns);

        var btn = document.getElementById('saveSectionsBtn');
        if (btn) btn.classList.add('is-busy');
        if (window.adminTestShowLoader) adminTestShowLoader('Saving sections and assignments…');
    };

    renderSections();
    loadAssignments();
})();
</script>
</body>
</html>
