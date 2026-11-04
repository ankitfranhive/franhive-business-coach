<!DOCTYPE html>
<html>
<?php $this->load->view('includes/header'); ?>
<?php $this->load->view('partials/admin_test_loading'); ?>

<div class="mobile-menu-overlay"></div>

<div class="main-container">
    <div class="pd-ltr-20 xs-pd-20-10">
        <div class="min-height-200px">
            <div class="page-header">
                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <div class="title">
                            <h4>Listing of all Questions</h4>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12 text-right">
                        <a class="btn btn-warning" href="<?= base_url('/add-question'); ?>">Add new Question</a>
                    </div>
                </div>
            </div>
            <div class="card-box mb-30">
                <div class="pb-20">
                    <table id="admin-questions-list-table" class="table stripe hover nowrap w-100">
                        <thead>
                            <tr>
                                <th>Question Id</th>
                                <th>Question Name</th>
                                <th>Question Group</th>
                                <th>Marks</th>
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
    var ajaxUrl = '<?= base_url('admin-test-questions-datatable') ?>';
    function escapeHtml(s) {
        return $('<div/>').text(s == null ? '' : s).html();
    }
    $(document).ready(function () {
        if (!$.fn.DataTable) return;
        var table = $('#admin-questions-list-table').DataTable({
            processing: true,
            serverSide: true,
            deferRender: true,
            scrollCollapse: true,
            autoWidth: false,
            responsive: true,
            order: [[0, 'desc']],
            ajax: {
                url: ajaxUrl,
                type: 'POST',
                data: function (d) { d.test_id = 0; }
            },
            columns: [
                { data: 'QUESTION_ID' },
                { data: 'QUESTION_NAME', render: function (d) { return escapeHtml(d); } },
                { data: 'QUESTION_GROUP', render: function (d) { return escapeHtml(d); } },
                { data: 'QUESTION_MARKS', render: function (d) { return escapeHtml(String(d)); } },
                {
                    data: null, orderable: false, searchable: false,
                    render: function (d, t, row) {
                        var edit = '<?= base_url('edit-quetsion/') ?>' + row.QUESTION_ID;
                        var del = '<?= base_url('admin/delete_question/') ?>' + row.QUESTION_ID;
                        return '<a href="' + edit + '" class="btn btn-primary btn-sm">Edit</a> ' +
                            '<a href="' + del + '" class="btn btn-danger btn-sm">Delete</a>';
                    }
                }
            ],
            lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
            pageLength: 25,
            language: { processing: 'Loading questions…' }
        });
        table.on('draw', function () {
            if (window.adminTestHideLoader) window.adminTestHideLoader();
        });
    });
})();
</script>
</html>
