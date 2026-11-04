<!DOCTYPE html>
<html>
<?php $this->load->view('includes/header'); ?>
<?php $this->load->view('partials/admin_test_loading'); ?>

<style>
    .allocation-filters label {
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
                            <h4>Test allocation to users</h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-box mb-30">
                <div class="pd-20 pb-0">
                    <div class="row allocation-filters align-items-end">
                        <div class="col-md-4 col-sm-6 mb-3">
                            <label for="filter-allocation">Allocated tests</label>
                            <select id="filter-allocation" class="form-control form-control-sm">
                                <option value="">All users</option>
                                <option value="has">Has active allocations</option>
                                <option value="none">No allocations yet</option>
                            </select>
                        </div>
                        <div class="col-md-4 col-sm-6 mb-3">
                            <button type="button" id="filter-reset" class="btn btn-outline-secondary btn-sm">Reset filters</button>
                        </div>
                    </div>
                </div>
                <div class="pb-20">
                    <table id="test-allocation-users-table" class="table stripe hover nowrap w-100">
                        <thead>
                            <tr>
                                <th>User ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Active tests</th>
                                <th class="datatable-nosort">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user) :
                                $uid = (int) $user->USER_ID;
                                $alloc = (int) ($user->ALLOCATED_TESTS ?? 0);
                            ?>
                                <tr data-allocation="<?= $alloc > 0 ? 'has' : 'none' ?>">
                                    <td><?= $uid ?></td>
                                    <td><?= htmlspecialchars($user->NAME ?? '') ?></td>
                                    <td><?= htmlspecialchars($user->EMAIL ?? '') ?></td>
                                    <td><?= $alloc ?></td>
                                    <td>
                                        <a href="<?= base_url('test-allocation/' . $uid) ?>" class="btn btn-primary btn-sm">Assign tests</a>
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
    function initTable() {
        if (!$.fn.DataTable || !$('#test-allocation-users-table').length) return null;
        if ($.fn.DataTable.isDataTable('#test-allocation-users-table')) {
            return $('#test-allocation-users-table').DataTable();
        }
        var table = $('#test-allocation-users-table').DataTable({
            processing: true,
            deferRender: true,
            scrollCollapse: true,
            autoWidth: false,
            responsive: true,
            order: [[0, 'desc']],
            columnDefs: [{ targets: 'datatable-nosort', orderable: false, searchable: false }],
            lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
            pageLength: 25,
            language: {
                processing: 'Loading users…',
                info: '_START_–_END_ of _TOTAL_',
                searchPlaceholder: 'Search users…',
                paginate: {
                    next: '<i class="ion-chevron-right"></i>',
                    previous: '<i class="ion-chevron-left"></i>'
                }
            }
        });

        $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
            if (settings.nTable.id !== 'test-allocation-users-table') return true;
            var filter = $('#filter-allocation').val() || '';
            if (!filter) return true;
            var row = table.row(dataIndex).node();
            if (!row) return true;
            return $(row).data('allocation') === filter;
        });

        $('#filter-allocation').on('change', function () { table.draw(); });
        $('#filter-reset').on('click', function () {
            $('#filter-allocation').val('');
            table.search('').draw();
        });

        return table;
    }
    $(document).ready(initTable);
})();
</script>
</html>
