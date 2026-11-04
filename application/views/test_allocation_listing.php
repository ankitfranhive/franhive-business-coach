<!DOCTYPE html>
<html>
<?php $this->load->view('includes/header'); ?>
<?php $this->load->view('partials/admin_test_loading'); ?>

<?php
$user_id = isset($user_id) ? (int) $user_id : 0;
$user_name = !empty($user->NAME) ? $user->NAME : ('User #' . $user_id);
?>

<style>
    .allocation-filters label { font-size: 12px; font-weight: 600; margin-bottom: 4px; }
    .data-table td { white-space: normal !important; word-break: break-word; }
    .pb-20 { overflow-x: auto; }
    .tooltip-flash {
        visibility: hidden;
        background-color: #155724;
        color: #fff;
        border-radius: 4px;
        padding: 2px 6px;
        font-size: 11px;
        margin-left: 6px;
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
                            <h4>Assign tests: <?= htmlspecialchars($user_name) ?></h4>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12 text-right">
                        <a class="btn btn-warning" href="<?= base_url('test-allocation') ?>">Back to users</a>
                    </div>
                </div>
            </div>

            <div class="card-box mb-30">
                <div class="pd-20 pb-0">
                    <div class="row allocation-filters align-items-end">
                        <div class="col-md-3 col-sm-6 mb-3">
                            <label for="filter-test-type">Test type</label>
                            <select id="filter-test-type" class="form-control form-control-sm">
                                <option value="">All types</option>
                                <option value="1">Type 1 (Profiling)</option>
                                <option value="2">Type 2</option>
                                <option value="3">Type 3</option>
                            </select>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <label for="filter-assign-status">Assignment</label>
                            <select id="filter-assign-status" class="form-control form-control-sm">
                                <option value="">All tests</option>
                                <option value="unassigned">Not assigned</option>
                                <option value="active">Assigned (active)</option>
                                <option value="removed">Removed / reassign</option>
                            </select>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <button type="button" id="filter-reset" class="btn btn-outline-secondary btn-sm">Reset filters</button>
                        </div>
                        <div class="col-md-3 col-sm-12 mb-3 text-md-right">
                            <button type="button" id="bulk-assign-tests" class="btn btn-success btn-sm" disabled>
                                Assign selected to user
                            </button>
                            <span id="bulk-selected-count" class="text-muted small ml-1"></span>
                        </div>
                    </div>
                </div>
                <div class="pb-20">
                    <table id="test-allocation-tests-table" class="table stripe hover nowrap w-100">
                        <thead>
                            <tr>
                                <th class="datatable-nosort text-center" style="width:36px;">
                                    <input type="checkbox" id="select-all-page" title="Select all on this page (not yet assigned)">
                                </th>
                                <th>ID</th>
                                <th>Test name</th>
                                <th>Questions</th>
                                <th>Marks</th>
                                <th>Start date</th>
                                <th>Status</th>
                                <th class="datatable-nosort">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($tests as $test) :
                                $tid = (int) $test->TEST_ID;
                                $ttype = (int) ($test->TEST_TYPE ?? 0);
                                $alloc_is_removed = (int) ($test->IS_REMOVED ?? $test->is_removed ?? 0);
                                $has_user = $test->USER_ID !== null && $test->USER_ID !== '';
                                if (!$has_user) {
                                    $assign_status = 'unassigned';
                                    $status_label = 'Not assigned';
                                } elseif ($alloc_is_removed === 1) {
                                    $assign_status = 'removed';
                                    $status_label = 'Removed';
                                } else {
                                    $assign_status = 'active';
                                    $status_label = 'Assigned';
                                }
                            ?>
                                <tr
                                    data-test-type="<?= $ttype ?>"
                                    data-assign-status="<?= $assign_status ?>"
                                    data-test-id="<?= $tid ?>"
                                >
                                    <td class="text-center">
                                        <?php if ($assign_status === 'unassigned') : ?>
                                            <input type="checkbox" class="row-select-test" value="<?= $tid ?>">
                                        <?php endif; ?>
                                    </td>
                                    <td><?= $tid ?></td>
                                    <td><?= htmlspecialchars($test->TEST_NAME ?? '') ?></td>
                                    <td><?= htmlspecialchars((string) ($test->TOTAL_QUESTIONS ?? '')) ?></td>
                                    <td><?= htmlspecialchars((string) ($test->TOTAL_MARKS ?? '')) ?></td>
                                    <td><?= htmlspecialchars((string) ($test->TEST_START_DATE ?? '')) ?></td>
                                    <td class="col-assign-status"><?= $status_label ?></td>
                                    <td class="col-actions">
                                        <?php if ($assign_status === 'active') : ?>
                                            <button type="button" class="btn btn-danger btn-sm remove-test-user" data-is-removed="1" data-mapping-id="<?= (int) $test->MAPPING_ID ?>">Remove test</button>
                                        <?php elseif ($assign_status === 'removed') : ?>
                                            <button type="button" class="btn btn-warning btn-sm remove-test-user" data-is-removed="0" data-mapping-id="<?= (int) $test->MAPPING_ID ?>">Re-assign test</button>
                                        <?php else : ?>
                                            <button type="button" class="btn btn-primary btn-sm add-test-user" data-test-id="<?= $tid ?>">Assign to user</button>
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
    var userId = <?= (int) $user_id ?>;
    var table = null;

    function selectedTestIds() {
        var ids = [];
        $('#test-allocation-tests-table tbody .row-select-test:checked').each(function () {
            ids.push(parseInt($(this).val(), 10));
        });
        return ids.filter(function (id) { return id > 0; });
    }

    function updateBulkUi() {
        var n = selectedTestIds().length;
        $('#bulk-assign-tests').prop('disabled', n === 0);
        $('#bulk-selected-count').text(n ? (n + ' selected') : '');
    }

    function initTable() {
        if (!$.fn.DataTable || !$('#test-allocation-tests-table').length) return null;
        if ($.fn.DataTable.isDataTable('#test-allocation-tests-table')) {
            return $('#test-allocation-tests-table').DataTable();
        }
        table = $('#test-allocation-tests-table').DataTable({
            processing: true,
            deferRender: true,
            scrollCollapse: true,
            autoWidth: false,
            responsive: true,
            order: [[1, 'desc']],
            columnDefs: [
                { targets: 0, orderable: false, searchable: false },
                { targets: 'datatable-nosort', orderable: false, searchable: false }
            ],
            lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
            pageLength: 25,
            language: {
                processing: 'Loading tests…',
                info: '_START_–_END_ of _TOTAL_',
                searchPlaceholder: 'Search tests…',
                paginate: {
                    next: '<i class="ion-chevron-right"></i>',
                    previous: '<i class="ion-chevron-left"></i>'
                }
            }
        });

        $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
            if (settings.nTable.id !== 'test-allocation-tests-table') return true;
            var typeFilter = $('#filter-test-type').val() || '';
            var statusFilter = $('#filter-assign-status').val() || '';
            var row = table.row(dataIndex).node();
            if (!row) return true;
            var $row = $(row);
            if (typeFilter && String($row.data('test-type')) !== typeFilter) return false;
            if (statusFilter && $row.data('assign-status') !== statusFilter) return false;
            return true;
        });

        $('#filter-test-type, #filter-assign-status').on('change', function () {
            $('#select-all-page').prop('checked', false);
            table.draw();
            updateBulkUi();
        });
        $('#filter-reset').on('click', function () {
            $('#filter-test-type, #filter-assign-status').val('');
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

    $(document).ready(function () {
        initTable();

        $(document).on('change', '.row-select-test', updateBulkUi);

        $('#select-all-page').on('change', function () {
            var checked = this.checked;
            var nodes = table.rows({ page: 'current', search: 'applied' }).nodes();
            $(nodes).find('.row-select-test').prop('checked', checked);
            updateBulkUi();
        });

        $('#bulk-assign-tests').on('click', function () {
            var ids = selectedTestIds();
            if (!ids.length || !userId) return;
            if (!confirm('Assign ' + ids.length + ' test(s) to this user?')) return;
            var $btn = $(this).prop('disabled', true);
            $.ajax({
                url: '<?= base_url('bulk-save-test-user') ?>',
                method: 'POST',
                dataType: 'json',
                data: { user_id: userId, test_ids: ids },
                success: function (res) {
                    alert('Assigned: ' + (res.added || 0) + ', skipped: ' + (res.skipped || 0));
                    window.location.reload();
                },
                error: function () {
                    alert('Bulk assign failed.');
                    $btn.prop('disabled', false);
                }
            });
        });

        $(document).on('click', '.add-test-user', function () {
            var button = $(this);
            var testId = button.data('test-id');
            $.ajax({
                url: '<?= base_url('save-test-user') ?>',
                method: 'POST',
                data: { test_id: testId, user_id: userId },
                success: function () { window.location.reload(); },
                error: function () { alert('Could not assign test.'); }
            });
        });

        $(document).on('click', '.remove-test-user', function () {
            var button = $(this);
            $.ajax({
                url: '<?= base_url('remove-test-user') ?>',
                method: 'POST',
                data: {
                    mapping_id: button.data('mapping-id'),
                    user_id: userId,
                    is_removed: button.data('is-removed')
                },
                success: function () { window.location.reload(); },
                error: function () { alert('Action failed.'); }
            });
        });
    });
})();
</script>
</html>
