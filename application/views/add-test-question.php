<!DOCTYPE html>
<html>
<?php $this->load->view('includes/header'); ?>

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
                        <tbody>
                            <?php foreach ($questions as $question) :
                                $qid = (int) $question->QUESTION_ID;
                                $qtype = (int) ($question->QUESTION_TYPE ?? 2);
                                $group = trim($question->QUESTION_GROUP ?? '');
                                $in_test = !empty($question->MAPPING_ID) ? 1 : 0;
                                $name_plain = strip_tags($question->QUESTION_NAME ?? '');
                            ?>
                                <tr
                                    data-question-id="<?= $qid ?>"
                                    data-group="<?= htmlspecialchars($group, ENT_QUOTES, 'UTF-8') ?>"
                                    data-type="<?= $qtype ?>"
                                    data-in-test="<?= $in_test ?>"
                                >
                                    <?php if ($test_id > 0) : ?>
                                    <td class="text-center">
                                        <?php if (!$in_test) : ?>
                                            <input type="checkbox" class="row-select-question" value="<?= $qid ?>">
                                        <?php endif; ?>
                                    </td>
                                    <?php endif; ?>
                                    <td><?= $qid ?></td>
                                    <td><?= htmlspecialchars($name_plain) ?></td>
                                    <td><?= htmlspecialchars($group) ?></td>
                                    <td><?= $qtype === 1 ? 'MCQ' : 'Subjective' ?></td>
                                    <td><?= htmlspecialchars((string) ($question->QUESTION_MARKS ?? '')) ?></td>
                                    <?php if ($test_id > 0) : ?>
                                    <td class="col-in-test"><?= $in_test ? 'In test' : '—' ?></td>
                                    <?php endif; ?>
                                    <td class="col-actions">
                                        <?php if ($test_id > 0) : ?>
                                            <?php if ($in_test) : ?>
                                                <span class="badge badge-success">Added</span>
                                            <?php else : ?>
                                                <button type="button" class="btn btn-primary btn-sm add-question" data-question-id="<?= $qid ?>">Add to test</button>
                                                <span class="tooltip-added" id="tooltip_<?= $qid ?>">Added!</span>
                                            <?php endif; ?>
                                        <?php else : ?>
                                            <span class="text-muted">—</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
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
    var filterFn = null;

    function initTable() {
        if (!$.fn.DataTable || $('#test-questions-table').length === 0) {
            return null;
        }
        if ($.fn.DataTable.isDataTable('#test-questions-table')) {
            return $('#test-questions-table').DataTable();
        }

        var idColIndex = testId > 0 ? 1 : 0;
        var columnDefs = [{ targets: 'datatable-nosort', orderable: false, searchable: false }];
        if (testId > 0) {
            columnDefs.unshift({ targets: 0, orderable: false, searchable: false });
        }
        var table = $('#test-questions-table').DataTable({
            scrollCollapse: true,
            autoWidth: false,
            responsive: true,
            order: [[idColIndex, 'desc']],
            columnDefs: columnDefs,
            lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
            pageLength: 25,
            language: {
                info: '_START_–_END_ of _TOTAL_',
                searchPlaceholder: 'Search questions…',
                paginate: {
                    next: '<i class="ion-chevron-right"></i>',
                    previous: '<i class="ion-chevron-left"></i>'
                }
            }
        });

        filterFn = function (settings, data, dataIndex) {
            if (settings.nTable.id !== 'test-questions-table') {
                return true;
            }
            var row = table.row(dataIndex).node();
            if (!row) return true;
            var $row = $(row);
            var groupVal = $('#filter-group').val() || '';
            var typeVal = $('#filter-type').val() || '';
            var inTestVal = $('#filter-in-test').length ? ($('#filter-in-test').val() || '') : '';

            if (groupVal && $row.data('group') !== groupVal) return false;
            if (typeVal && String($row.data('type')) !== typeVal) return false;
            if (inTestVal === '1' && Number($row.data('in-test')) !== 1) return false;
            if (inTestVal === '0' && Number($row.data('in-test')) !== 0) return false;
            return true;
        };
        $.fn.dataTable.ext.search.push(filterFn);

        $('#filter-group, #filter-type, #filter-in-test').on('change', function () {
            table.draw();
        });
        $('#filter-reset').on('click', function () {
            $('#filter-group, #filter-type, #filter-in-test').val('');
            $('#select-all-page').prop('checked', false);
            table.search('').draw();
            updateBulkUi();
        });

        table.on('draw', function () {
            $('#select-all-page').prop('checked', false);
            updateBulkUi();
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

    function markQuestionRowAdded($row) {
        $row.attr('data-in-test', '1');
        $row.data('in-test', 1);
        $row.find('.row-select-question').remove();
        $row.find('.col-in-test').text('In test');
        $row.find('.col-actions').html('<span class="badge badge-success">Added</span>');
    }

    $(document).ready(function () {
        var table = initTable();

        $(document).on('change', '.row-select-question', updateBulkUi);

        $('#select-all-page').on('change', function () {
            if (!table) return;
            var checked = this.checked;
            var nodes = table.rows({ page: 'current', search: 'applied' }).nodes();
            $(nodes).find('.row-select-question').prop('checked', checked);
            updateBulkUi();
        });

        $('#bulk-add-questions').on('click', function () {
            var ids = selectedQuestionIds();
            if (!ids.length || !testId) return;
            if (!confirm('Add ' + ids.length + ' question(s) to this test?')) return;
            var $btn = $(this).prop('disabled', true);
            $.ajax({
                url: '<?= base_url('bulk-save-question-test') ?>',
                method: 'POST',
                dataType: 'json',
                data: { test_id: testId, question_ids: ids },
                success: function (res) {
                    (res.question_ids || []).forEach(function (qid) {
                        var $row = $('#test-questions-table tbody tr[data-question-id="' + qid + '"]');
                        if ($row.length) markQuestionRowAdded($row);
                    });
                    alert('Added: ' + (res.added || 0) + ', skipped: ' + (res.skipped || 0));
                    $('#select-all-page').prop('checked', false);
                    updateBulkUi();
                    $btn.prop('disabled', false);
                    if (table) table.draw(false);
                },
                error: function () {
                    alert('Bulk add failed.');
                    $btn.prop('disabled', false);
                    updateBulkUi();
                }
            });
        });

        $(document).on('click', '.add-question', function () {
            if (!testId) return;
            var button = $(this);
            var questionId = button.data('question-id');
            var tooltip = $('#tooltip_' + questionId);
            var $row = button.closest('tr');

            $.ajax({
                url: '<?= base_url('save-question-test') ?>',
                method: 'POST',
                data: {
                    question_id: questionId,
                    test_id: testId
                },
                success: function () {
                    markQuestionRowAdded($row);
                    tooltip.css('visibility', 'visible');
                    setTimeout(function () { tooltip.css('visibility', 'hidden'); }, 2500);
                    updateBulkUi();
                    if (table) table.draw(false);
                },
                error: function (xhr) {
                    console.error(xhr);
                    alert('Could not add question. It may already be in this test.');
                }
            });
        });
    });
})();
</script>
</html>
