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
                            <h4>Listing of Agreements</h4>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12 text-right">
                        <a class="btn btn-warning" href="<?= base_url('/payment-agreement'); ?>" role="button">
                            Add Payment Agreement
                        </a>
                    </div>
                </div>
            </div>

            <?php if ($this->session->flashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= $this->session->flashdata('success'); ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>

            <?php if ($this->session->flashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= $this->session->flashdata('error'); ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>

            <div class="card-box mb-30">
                <div class="pd-20"></div>
                <div class="pb-20">
                    <table class="data-table table stripe hover nowrap">
                        <thead>
                            <tr>
                                <th>Agreement ID</th>
                                <th>Client Name</th>
                                <th>Created At</th>
                                <th class="datatable-nosort">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($all_agreements as $agreement): ?>
                                <tr>
                                    <td><?= $agreement['id'] ?></td>
                                    <td><?= $agreement['full_name'] ?></td>
                                    <td><?= $agreement['created_at'] ?></td>
                                    <td class="no-wrap">
                                        <div class="dropdown">
                                            <a
                                                class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle"
                                                href="#"
                                                role="button"
                                                data-toggle="dropdown"
                                            >
                                                <i class="dw dw-more"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                                <a href="<?= base_url('payment-agreement/preview/' . $agreement['id']); ?>" target="_blank" class="dropdown-item">
                                                    <i class="dw dw-eye"></i> Preview Filled Form
                                                </a>
                                                <a class="dropdown-item" href="<?php echo base_url('roles/'.$agreement['id'].''); ?>">
                                                    <i class="dw dw-eye"></i> View
                                                </a>
                                                <a class="dropdown-item" href="<?php echo base_url('edit-agreement/'.$agreement['id'].''); ?>">
                                                    <i class="dw dw-edit2"></i> Edit
                                                </a>
                                                <a class="dropdown-item" href="<?php echo base_url('delete-role/'.$agreement['id'].''); ?>">
                                                    <i class="dw dw-delete-3"></i> Delete
                                                </a>
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

</body>
<?php $this->load->view('includes/footer'); ?>
<script src="src/plugins/datatables/js/jquery.dataTables.min.js"></script>
<script src="src/plugins/datatables/js/dataTables.bootstrap4.min.js"></script>
<script src="src/plugins/datatables/js/dataTables.responsive.min.js"></script>
<script src="src/plugins/datatables/js/responsive.bootstrap4.min.js"></script>
<script src="src/plugins/datatables/js/dataTables.buttons.min.js"></script>
<script src="src/plugins/datatables/js/buttons.bootstrap4.min.js"></script>
<script src="src/plugins/datatables/js/buttons.print.min.js"></script>
<script src="src/plugins/datatables/js/buttons.html5.min.js"></script>
<script src="src/plugins/datatables/js/buttons.flash.min.js"></script>
<script src="src/plugins/datatables/js/pdfmake.min.js"></script>
<script src="src/plugins/datatables/js/vfs_fonts.js"></script>
<script src="vendors/scripts/datatable-setting.js"></script>
</html>