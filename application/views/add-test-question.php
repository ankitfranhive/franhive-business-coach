<!DOCTYPE html>
<html>
<?php $this->load->view('includes/header'); ?>
<?php $this->load->view('partials/admin_test_loading'); ?>

<?php
$test_id = isset($test_id) ? (int) $test_id : 0;
$test_name = !empty($test['TEST_NAME']) ? $test['TEST_NAME'] : '';
?>

<style>
    .tooltip-added {
        visibility: hidden;
        background-color: #155724;
        color: #fff;
        text-align: center;
        border-radius: 4px;
        padding: 4px 8px;
        font-size: 12px;
        margin-left: 8px;
        display: inline-block;
    }
    .test-question-filters .form-group {
        margin-bottom: 0;
    }
    .test-question-filters label {
        font-size: 12px;
        font-weight: 600;
        margin-bottom: 4px;
    }
</style>

<div class="mobile-menu-overlay"></div>

<div class="main-container">
    <div class="pd-ltr-20 xs-pd-20-10">
        <div class="min-height-200px">
            <div class="page-header">
                <div class="row">
                    <div class="col-md-8 col-sm-12">
                        <div class="title">
                            <h4>Add questions to test<?= $test_name !== '' ? ': ' . htmlspecialchars($test_name) : '' ?></h4>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12 text-right">
                        <a class="btn btn-warning" href="<?= base_url('test') ?>">Back to tests</a>
                    </div>
                </div>
            </div>

            <div class="card-box mb-30">
                <div class="pd-20 pb-0">
                    <div class="row test-question-filters align-items-end">
                        <div class="col-md-3 col-sm-6 mb-3">
                            <label for="filter-group">Question group</label>
                            <select id="filter-group" class="form-control form-control-sm">
                                <option value="">All groups</option>
                                <?php if (!empty($question_groups)) : ?>
                                    <?php foreach ($question_groups as $g) :
                                        $grp = trim($g->QUESTION_GROUP ?? '');
                                        if ($grp === '') continue;
                                    ?>
                                        <option value="<?= htmlspecialchars($grp, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($grp) ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <label for="filter-type">Question type</label>
                            <select id="filter-type" class="form-control form-control-sm">
                                <option value="">All types</option>
                                <option value="1">MCQ</option>
                                <option value="2">Subjective</option>
                            </select>
                        </div>
                        <?php if ($test_id > 0) : ?>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <label for="filter-in-test">In this test</label>
                            <select id="filter-in-test" class="form-control form-control-sm">
                                <option value="">All questions</option>
                                <option value="1">Already in test</option>
                                <option value="0">Not in test yet</option>
                            </select>
                        </div>
                        <?php endif; ?>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <button type="button" id="filter-reset" class="btn btn-outline-secondary btn-sm">Reset filters</button>
                        </div>
                        <?php if ($test_id > 0) : ?>
                        <div class="col-md-12 col-lg-6 mb-3 text-lg-right">
                            <button type="button" id="bulk-add-questions" class="btn btn-success btn-sm" disabled>
                                Add selected to test
                            </button>
                            <span id="bulk-selected-count" class="text-muted small ml-1"></span>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="pb-20">
                    <table id="test-questions-table" class="test-questions-datatable table stripe hover nowrap w-100">
                        <thead>
                            <tr>
                                <?php if ($test_id > 0) : ?>
                                <th class="datatable-nosort text-center" style="width:36px;">
                                    <input type="checkbox" id="select-all-page" title="Select all on this page (not in test yet)">
                                </th>
                                <?php endif; ?>
                                <th>ID</th>
                                <th>Question</th>
                                <th>Group</th>
                                <th>Type</th>
                                <th>Marks</th>
                                <?php if ($test_id > 0) : ?><th>Status</th><?php endif; ?>
                                <th class="datatable-nosort">Actions</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
<?php $this->load->view('includes/footer'); ?>
<script>
(function () {
    var testId = <?= (int) $test_id ?>;
    var ajaxUrl = '<?= base_url('admin-test-questions-datatable') ?>';
    var table = null;

    function escapeHtml(s) {
        return $('<div/>').text(s == null ? '' : s).html();
    }

    function buildColumns() {
        if (testId > 0) {
            return [
                {
                    data: null, orderable: false, searchable: false,
                    render: function (d, t, row) {
                        if (row.IN_TEST) return '';
                        return '<input type="checkbox" class="row-select-question" value="' + row.QUESTION_ID + '">';
                    }
                },
                { data: 'QUESTION_ID' },
                { data: 'QUESTION_NAME', render: function (d) { return escapeHtml(d); } },
                { data: 'QUESTION_GROUP', render: function (d) { return escapeHtml(d); } },
                { data: 'QUESTION_TYPE', render: function (d) { return Number(d) === 1 ? 'MCQ' : 'Subjective'; } },
                { data: 'QUESTION_MARKS', render: function (d) { return escapeHtml(String(d)); } },
                { data: 'IN_TEST', className: 'col-in-test', render: function (d) { return d ? 'In test' : '—'; } },
                {
                    data: null, orderable: false, searchable: false, className: 'col-actions',
                    render: function (d, t, row) {
                        if (row.IN_TEST) {
                            return '<span class="badge badge-success">Added</span>';
                        }
                        return '<button type="button" class="btn btn-primary btn-sm add-question" data-question-id="' + row.QUESTION_ID + '">Add to test</button>';
                    }
                }
            ];
        }
        return [
            { data: 'QUESTION_ID' },
            { data: 'QUESTION_NAME', render: function (d) { return escapeHtml(d); } },
            { data: 'QUESTION_GROUP', render: function (d) { return escapeHtml(d); } },
            { data: 'QUESTION_TYPE', render: function (d) { return Number(d) === 1 ? 'MCQ' : 'Subjective'; } },
            { data: 'QUESTION_MARKS', render: function (d) { return escapeHtml(String(d)); } },
            {
                data: null, orderable: false, searchable: false,
                render: function () { return '<span class="text-muted">—</span>'; }
            }
        ];
    }

    function initTable() {
        if (!$.fn.DataTable || !$('#test-questions-table').length) {
            return null;
        }
        if ($.fn.DataTable.isDataTable('#test-questions-table')) {
            return $('#test-questions-table').DataTable();
        }

        var idColIndex = testId > 0 ? 1 : 0;
        table = $('#test-questions-table').DataTable({
            processing: true,
            serverSide: true,
            deferRender: true,
            scrollCollapse: true,
            autoWidth: false,
            responsive: true,
            order: [[idColIndex, 'asc']],
            ajax: {
                url: ajaxUrl,
                type: 'POST',
                data: function (d) {
                    d.test_id = testId;
                    d.filter_group = $('#filter-group').val() || '';
                    d.filter_type = $('#filter-type').val() || '';
                    d.filter_in_test = $('#filter-in-test').length ? ($('#filter-in-test').val() || '') : '';
                }
            },
            columns: buildColumns(),
            lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
            pageLength: 25,
            language: {
                processing: 'Loading questions…',
                info: '_START_–_END_ of _TOTAL_',
                searchPlaceholder: 'Search questions…',
                paginate: {
                    next: '<i class="ion-chevron-right"></i>',
                    previous: '<i class="ion-chevron-left"></i>'
                }
            },
            rowCallback: function (row, data) {
                $(row).attr('data-question-id', data.QUESTION_ID);
            }
        });

        $('#filter-group, #filter-type, #filter-in-test').on('change', function () {
            table.ajax.reload();
        });
        $('#filter-reset').on('click', function () {
            $('#filter-group, #filter-type, #filter-in-test').val('');
            $('#select-all-page').prop('checked', false);
            table.search('').ajax.reload();
            updateBulkUi();
        });

        table.on('draw', function () {
            $('#select-all-page').prop('checked', false);
            updateBulkUi();
            if (window.adminTestHideLoader) {
                window.adminTestHideLoader();
            }
        });

        return table;
    }

    function selectedQuestionIds() {
        var ids = [];
        $('#test-questions-table tbody .row-select-question:checked').each(function () {
            ids.push(parseInt($(this).val(), 10));
        });
        return ids.filter(function (id) { return id > 0; });
    }

    function updateBulkUi() {
        if (!testId) return;
        var n = selectedQuestionIds().length;
        $('#bulk-add-questions').prop('disabled', n === 0);
        $('#bulk-selected-count').text(n ? (n + ' selected') : '');
    }

    $(document).ready(function () {
        table = initTable();

        $(document).on('change', '.row-select-question', updateBulkUi);

        $('#select-all-page').on('change', function () {
            if (!table) return;
            var checked = this.checked;
            $('#test-questions-table tbody .row-select-question').prop('checked', checked);
            updateBulkUi();
        });

        $('#bulk-add-questions').on('click', function () {
            var ids = selectedQuestionIds();
            if (!ids.length || !testId) return;
            if (!confirm('Add ' + ids.length + ' question(s) to this test?')) return;
            var $btn = $(this).prop('disabled', true);
            if (window.adminTestShowLoader) window.adminTestShowLoader('Adding questions…');
            $.ajax({
                url: '<?= base_url('bulk-save-question-test') ?>',
                method: 'POST',
                dataType: 'json',
                data: { test_id: testId, question_ids: ids },
                success: function (res) {
                    alert('Added: ' + (res.added || 0) + ', skipped: ' + (res.skipped || 0));
                    $('#select-all-page').prop('checked', false);
                    updateBulkUi();
                    $btn.prop('disabled', false);
                    if (table) table.ajax.reload(null, false);
                },
                error: function () {
                    alert('Bulk add failed.');
                    $btn.prop('disabled', false);
                    updateBulkUi();
                },
                complete: function () {
                    if (window.adminTestHideLoader) window.adminTestHideLoader();
                }
            });
        });

        $(document).on('click', '.add-question', function () {
            if (!testId) return;
            var button = $(this);
            var questionId = button.data('question-id');
            button.prop('disabled', true);
            $.ajax({
                url: '<?= base_url('save-question-test') ?>',
                method: 'POST',
                data: { question_id: questionId, test_id: testId },
                success: function () {
                    updateBulkUi();
                    if (table) table.ajax.reload(null, false);
                },
                error: function () {
                    alert('Could not add question. It may already be in this test.');
                    button.prop('disabled', false);
                }
            });
        });
    });
})();
</script>
</html>
