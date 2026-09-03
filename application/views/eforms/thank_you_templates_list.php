<!DOCTYPE html>
<html>
<?php $this->load->view('includes/header'); ?>

<div class="mobile-menu-overlay"></div>

<div class="main-container">
    <div class="pd-ltr-20 xs-pd-20-10">

        <div class="page-header">
            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <div class="title"><h4>Thank You Page Templates</h4></div>
                </div>
                <div class="col-md-6 col-sm-12 text-right">
                    <a class="btn btn-warning" href="<?= base_url('admin_eforms/thank_you_template_create'); ?>">
                        Add Thank You Page
                    </a>
                </div>
            </div>
        </div>

        <div class="pd-20 card-box mb-30">
            <?php if ($this->session->flashdata('success')): ?>
                <div class="alert alert-success"><?= html_escape($this->session->flashdata('success')); ?></div>
            <?php endif; ?>
            <?php if ($this->session->flashdata('error')): ?>
                <div class="alert alert-danger"><?= html_escape($this->session->flashdata('error')); ?></div>
            <?php endif; ?>

            <p class="text-muted mb-3">
                Design custom thank you pages here, then choose one when sending an eForm.
                If none is selected, the built-in default thank you page is shown.
            </p>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th style="width:70px;">ID</th>
                            <th>Title</th>
                            <th style="width:100px;">Status</th>
                            <th style="width:160px;">Updated</th>
                            <th style="width:180px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($templates)): ?>
                            <?php foreach ($templates as $t): ?>
                                <tr>
                                    <td><?= (int)$t['id']; ?></td>
                                    <td><?= html_escape($t['title']); ?></td>
                                    <td>
                                        <?php if (!empty($t['is_active'])): ?>
                                            <span class="badge badge-success">Active</span>
                                        <?php else: ?>
                                            <span class="badge badge-secondary">Inactive</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= html_escape($t['updated_at'] ?? $t['created_at'] ?? '-'); ?></td>
                                    <td>
                                        <a class="btn btn-sm btn-info"
                                           href="<?= base_url('admin_eforms/thank_you_template_edit/' . (int)$t['id']); ?>">
                                            Edit
                                        </a>
                                        <?php if (!empty($t['is_active'])): ?>
                                            <a class="btn btn-sm btn-danger"
                                               href="<?= base_url('admin_eforms/thank_you_template_delete/' . (int)$t['id']); ?>"
                                               onclick="return confirm('Deactivate this thank you page template?');">
                                                Deactivate
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted">
                                    No thank you page templates yet. Click “Add Thank You Page” to create one.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

</body>
<?php $this->load->view('includes/footer'); ?>
</html>
