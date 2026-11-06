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
                    <p class="text-muted mb-0">Section 1 → Value 2, Section 2 → Value 3, etc. Legacy tests without sections are unchanged.</p>
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
                    <p class="text-muted small mb-3" id="assignmentsMeta">
                        <?= $assignmentCount > 0
                            ? htmlspecialchars((string) $assignmentCount) . ' question(s) — loading list…'
                            : 'No questions mapped yet. Use Add questions.' ?>
                    </p>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm w-100" id="assignmentsTable">
                            <thead>
                                <tr>
                                    <th>Question</th>
                                    <th>Group</th>
                                    <th style="min-width: 200px">Section</th>
                                </tr>
                            </thead>
                            <tbody id="assignmentsBody">
                                <?php if ($assignmentCount <= 0): ?>
                                    <tr class="assignments-placeholder">
                                        <td colspan="3" class="text-muted">No questions mapped yet. Use Add questions.</td>
                                    </tr>
                                <?php else: ?>
                                    <tr class="assignments-placeholder">
                                        <td colspan="3" class="text-muted">Loading questions…</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
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
    var testId = <?= (int) $test_id ?>;
    var assignmentsUrl = <?= json_encode(base_url('test-section-assignments-json/' . $test_id)) ?>;
    var initialSections = <?= json_encode(array_map(function ($s) {
        return [
            'id' => (int) ($s['SECTION_ID'] ?? 0),
            'name' => $s['SECTION_NAME'] ?? '',
        ];
    }, $sectionsList)) ?>;

    var container = document.getElementById('sectionsEditor');
    var assignmentRows = [];
    var assignmentsTable = null;
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

    function destroyAssignmentsTable() {
        if (assignmentsTable && window.jQuery && jQuery.fn.DataTable) {
            assignmentsTable.destroy();
            assignmentsTable = null;
        }
    }

    function initAssignmentsTable() {
        if (!window.jQuery || !jQuery.fn.DataTable) {
            return;
        }
        assignmentsTable = jQuery('#assignmentsTable').DataTable({
            pageLength: 50,
            deferRender: true,
            order: [],
            autoWidth: false,
            language: { emptyTable: 'No questions mapped yet.' }
        });
    }

    function bindAssignmentSelect(tr, rowIndex) {
        var sel = tr.querySelector('.assign-section');
        if (!sel) return;
        sel.addEventListener('change', function () {
            if (assignmentRows[rowIndex]) {
                assignmentRows[rowIndex].section_index = sel.value;
            }
        });
    }

    function renderAssignmentRows() {
        var tbody = document.getElementById('assignmentsBody');
        destroyAssignmentsTable();
        tbody.innerHTML = '';

        if (!assignmentRows.length) {
            tbody.innerHTML = '<tr><td colspan="3" class="text-muted">No questions mapped yet. Use Add questions.</td></tr>';
            return;
        }

        var frag = document.createDocumentFragment();
        assignmentRows.forEach(function (row, idx) {
            var tr = document.createElement('tr');
            tr.setAttribute('data-mapping-id', String(row.mapping_id));
            tr.innerHTML =
                '<td>' + escapeHtml(row.question_label || '') + '</td>' +
                '<td>' + escapeHtml(row.question_group || '') + '</td>' +
                '<td><select class="form-control form-control-sm assign-section">' +
                sectionOptionsHtml(row.section_index !== undefined && row.section_index !== null ? row.section_index : '') +
                '</select></td>';
            bindAssignmentSelect(tr, idx);
            frag.appendChild(tr);
        });
        tbody.appendChild(frag);
        initAssignmentsTable();
    }

    function rebuildAssignmentDropdowns() {
        document.querySelectorAll('#assignmentsBody tr[data-mapping-id]').forEach(function (tr) {
            var mappingId = parseInt(tr.getAttribute('data-mapping-id'), 10);
            var sel = tr.querySelector('.assign-section');
            if (!sel) return;
            var row = assignmentRows.find(function (r) { return r.mapping_id === mappingId; });
            var prev = row ? row.section_index : sel.value;
            var maxIdx = container.querySelectorAll('.section-name').length;
            sel.innerHTML = sectionOptionsHtml('');
            if (prev !== '' && prev !== null && prev !== undefined && parseInt(prev, 10) >= 0 && parseInt(prev, 10) < maxIdx) {
                sel.value = String(prev);
                if (row) row.section_index = sel.value;
            }
        });
    }

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
                        question_label: r.question_label,
                        question_group: r.question_group,
                        section_index: r.section_index !== undefined && r.section_index !== null && r.section_index !== ''
                            ? String(r.section_index)
                            : ''
                    };
                });
                var meta = document.getElementById('assignmentsMeta');
                if (meta) {
                    meta.textContent = assignmentRows.length + ' question(s). Use search/pagination below; assignments are saved for all questions.';
                }
                renderAssignmentRows();
                assignmentsLoaded = true;
                if (window.adminTestHideLoader) adminTestHideLoader();
            })
            .catch(function (err) {
                var tbody = document.getElementById('assignmentsBody');
                tbody.innerHTML = '<tr><td colspan="3" class="text-danger">Could not load questions (' +
                    escapeHtml(err.message || 'error') + '). Refresh the page or try again in a moment.</td></tr>';
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
                '<input type="text" class="form-control section-name" data-id="' + (sec.id || '') + '" value="' + (sec.name || '').replace(/"/g, '&quot;') + '" placeholder="Section name (e.g. Section A)">' +
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
            };
        });
        container.querySelectorAll('.section-name').forEach(function (inp) {
            inp.oninput = function () {
                rebuildAssignmentDropdowns();
            };
        });
    }

    document.getElementById('addSectionRow').onclick = function () {
        initialSections.push({ id: 0, name: '' });
        renderSections();
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
