<!DOCTYPE html>
<html>
<?php $this->load->view('includes/header'); ?>
<?php $this->load->view('partials/admin_test_loading'); ?>
<?php
$test_id = (int) ($test_id ?? 0);
$testName = $test['TEST_NAME'] ?? ('Test #' . $test_id);
$sectionsList = $sections ?? [];
$mapped = $mapped_questions ?? [];
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
                    <h5 class="mb-3">Assign questions to sections</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead>
                                <tr>
                                    <th>Question</th>
                                    <th>Group</th>
                                    <th>Section</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($mapped as $mq): ?>
                                    <tr data-mapping-id="<?= (int) $mq['MAPPING_ID'] ?>">
                                        <td><?= strip_tags($mq['QUESTION_NAME'] ?? '') ?></td>
                                        <td><?= htmlspecialchars($mq['QUESTION_GROUP'] ?? '') ?></td>
                                        <td>
                                            <select class="form-control form-control-sm assign-section">
                                                <option value="">— Unassigned —</option>
                                                <?php foreach ($sectionsList as $si => $sec): ?>
                                                    <option value="<?= (int) $si ?>"
                                                        <?= (int)($mq['SECTION_ID'] ?? 0) === (int)$sec['SECTION_ID'] ? 'selected' : '' ?>>
                                                        <?= htmlspecialchars($sec['SECTION_NAME']) ?>
                                                        (Value <?= (int)($sec['SORT_ORDER'] ?? 0) + 1 ?>)
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <?php if (empty($mapped)): ?>
                                    <tr><td colspan="3" class="text-muted">No questions mapped yet. Use Add questions.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="pd-20">
                    <button type="submit" class="btn btn-success">Save sections &amp; assignments</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
(function () {
    var initialSections = <?= json_encode(array_map(function ($s) {
        return [
            'id' => (int) ($s['SECTION_ID'] ?? 0),
            'name' => $s['SECTION_NAME'] ?? '',
        ];
    }, $sectionsList)) ?>;

    var container = document.getElementById('sectionsEditor');

    function escapeHtml(s) {
        return String(s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/"/g, '&quot;');
    }

    function sectionOptionsHtml(selectedIndex) {
        var html = '<option value="">— Unassigned —</option>';
        container.querySelectorAll('.section-name').forEach(function (inp, idx) {
            var name = inp.value.trim() || ('Section ' + (idx + 1));
            var sel = String(selectedIndex) === String(idx) ? ' selected' : '';
            html += '<option value="' + idx + '"' + sel + '>' + escapeHtml(name) + ' (Value ' + (idx + 2) + ')</option>';
        });
        return html;
    }

    function rebuildAssignmentDropdowns() {
        document.querySelectorAll('tr[data-mapping-id]').forEach(function (tr) {
            var sel = tr.querySelector('.assign-section');
            if (!sel) return;
            var prev = sel.value;
            var maxIdx = container.querySelectorAll('.section-name').length;
            sel.innerHTML = sectionOptionsHtml('');
            if (prev !== '' && parseInt(prev, 10) >= 0 && parseInt(prev, 10) < maxIdx) {
                sel.value = prev;
            }
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
        var secs = [];
        container.querySelectorAll('.section-name').forEach(function (inp) {
            secs.push({ id: parseInt(inp.getAttribute('data-id'), 10) || 0, name: inp.value.trim() });
        });
        document.getElementById('sections_json').value = JSON.stringify(secs);

        var assigns = [];
        document.querySelectorAll('tr[data-mapping-id]').forEach(function (tr) {
            assigns.push({
                mapping_id: parseInt(tr.getAttribute('data-mapping-id'), 10),
                section_index: tr.querySelector('.assign-section').value
            });
        });
        document.getElementById('assignments_json').value = JSON.stringify(assigns);
    };

    renderSections();
})();
</script>
</body>
</html>
