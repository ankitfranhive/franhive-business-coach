<!DOCTYPE html>
<html>
<?php $this->load->view('includes/header'); ?>

<div class="mobile-menu-overlay"></div>

<div class="main-container">
    <div class="pd-ltr-20 xs-pd-20-10">
        <div class="min-height-200px">
            <div class="page-header">
                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <div class="title">
                            <h4>Listing of Client Type</h4>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12 text-right">
                        <a class="btn btn-warning" href="<?= base_url('/add-client-type'); ?>" role="button">
                            Add Client Type
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-box mb-30">
                <?php if ($error = $this->session->flashdata('error')): ?>
                    <div class="pd-20">
                        <div class="alert alert-danger">
                            <?= $error; ?>
                            <?php $this->session->unset_userdata('error'); // Clear the error flashdata 
                            ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($success = $this->session->flashdata('success')): ?>
                    <div class="pd-20">

                        <div class="alert alert-success">
                            <?= $success; ?>
                            <?php $this->session->unset_userdata('success'); // Clear the success flashdata 
                            ?>
                        </div>
                    </div>
                <?php endif; ?>
                <div class="pb-20">
                    <table class="data-table table stripe hover nowrap">
                        <thead>
                            <tr>

                                <th>ID</th>
                                <th>NAME</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($all_course as $course) : ?>

                                <tr>
                                <tr>
                                    </td>
                                    <td><?= $course['ID'] ?></td>
                                    <td><?= $course['NAME'] ?></td>
                                    <td class="no-wrap">
                                        <div class="dropdown">
                                            <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                                                <i class="dw dw-more"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                                <a class="dropdown-item" href="<?php echo base_url('/view-client-type/' . $course['ID'] . ''); ?>"><i class="dw dw-eye"></i> VIEW</a>
                                                <a class="dropdown-item" href="<?php echo base_url('/edit-client-type/' . $course['ID'] . ''); ?>"><i class="dw dw-edit2"></i> Edit</a>
                                                <a class="dropdown-item" href="<?php echo base_url('/delete-client-type/' . $course['ID'] . ''); ?>"><i class="dw dw-eye"></i> Delete</a>
                                            </div>
                                        </div>
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

<script type="text/javascript">
    $(document).ready(function() {
        // Hide the alert after 3 seconds (3000 milliseconds)
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 3000);
    });
</script>
</body>
<?php $this->load->view('includes/footer'); ?>
<script src="src/plugins/datatables/js/jquery.dataTables.min.js"></script>
<script src="src/plugins/datatables/js/dataTables.bootstrap4.min.js"></script>
<script src="src/plugins/datatables/js/dataTables.responsive.min.js"></script>
<script src="src/plugins/datatables/js/responsive.bootstrap4.min.js"></script>
<!-- buttons for Export datatable -->
<script src="src/plugins/datatables/js/dataTables.buttons.min.js"></script>
<script src="src/plugins/datatables/js/buttons.bootstrap4.min.js"></script>
<script src="src/plugins/datatables/js/buttons.print.min.js"></script>
<script src="src/plugins/datatables/js/buttons.html5.min.js"></script>
<script src="src/plugins/datatables/js/buttons.flash.min.js"></script>
<script src="src/plugins/datatables/js/pdfmake.min.js"></script>
<script src="src/plugins/datatables/js/vfs_fonts.js"></script>
<!-- Datatable Setting js -->
<script src="vendors/scripts/datatable-setting.js"></script>

</html>