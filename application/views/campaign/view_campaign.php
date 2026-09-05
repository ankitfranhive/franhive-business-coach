<!DOCTYPE html>
<html>
<?php $this->load->view('includes/header'); ?>
<?php $this->load->view('campaign/partials/campaign_styles'); ?>
<div class="mobile-menu-overlay"></div>
<div class="main-container">
    <div class="pd-ltr-20 xs-pd-20-10">
        <div class="page-header">
            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <div class="title">
                        <h4>View Campaign: <?= htmlspecialchars($campaign_data['TITLE']) ?></h4>
                        <?php $meta = campaign_status_meta($campaign_data['WORKFLOW_STATUS'] ?? 'draft'); ?>
                        <span class="badge <?= $meta['class'] ?>"><?= $meta['label'] ?></span>
                        <small class="text-muted"><?= $meta['help'] ?></small>
                    </div>
                </div>
                <div class="col-md-6 col-sm-12 text-right">
                    <a class="btn btn-warning" href="<?= base_url('/campaigns'); ?>" role="button">
                        Back To Campaign List
                    </a>
                </div>
            </div>
        </div>
        <div class="pd-20 card-box mb-30">
            <div class="clearfix">
                <div class="pull-left">
                    <!-- <h4 class="text-blue h4">Campaign Details</h4> -->
                </div>
            </div>

            <input type="hidden" name="CAMPAIGN_ID" value="<?= $campaign_data['CAMPAIGN_ID'] ?>">

            <!-- Campaign Details -->
            <div class="form-group row">
                <label class="col-sm-12 col-md-2 col-form-label">Name this campaign</label>
                <div class="col-sm-12 col-md-10">
                    <p class="form-control-static"><?= $campaign_data['TITLE'] ?></p>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-12 col-md-2 col-form-label">Who is it from?</label>
                <div class="col-sm-12 col-md-10">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="form-control-static"><?= $campaign_data['MANAGER_NAME'] ?></p>
                        </div>
                        <div class="col-md-6">
                            <p class="form-control-static">NLP@empoweryourdestiny.com.au</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-12 col-md-2 col-form-label">Create campaign for</label>
                <div class="col-sm-12 col-md-10">
                    <p class="form-control-static">
                        <?= htmlspecialchars(campaign_audience_label($campaign_data['MODULE_NAME'])) ?>
                    </p>
                </div>
            </div>
        </div>
        <div class="pd-20 card-box mb-30">
            <!-- Selected Templates -->

            <h5 class="card-title">Selected Templates</h5>
            <p class="text-muted">Times below show when each email will trigger in India, the Philippines, and Melbourne.</p>
            <div class="pb-20 mt-3">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Template</th>
                            <th>Subject</th>
                            <th>Order</th>
                            <th>Send date</th>
                            <th>Time and timezone</th>
                            <th>Triggers at</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($all_templates as $template): ?>
                            <?php if (in_array($template['TEMPLATE_ID'], array_column($selected_templates, 'TEMPLATE_ID'))): ?>
                                <?php $selected = $selected_templates[$template['TEMPLATE_ID']]; ?>
                                <tr>
                                    <td><?= htmlspecialchars($template['TEMPLATE_NAME']) ?></td>
                                    <td><?= htmlspecialchars($template['TEMPLATE_SUBJECT']) ?></td>
                                    <td><?= htmlspecialchars($selected['SENDING_ORDER'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($selected['SEND_DATE'] ?? '') ?></td>
                                    <td><?= htmlspecialchars(campaign_format_start_time($selected)) ?></td>
                                    <td><?= htmlspecialchars(campaign_send_equivalents($selected['SEND_AT'] ?? '')) ?></td>
                                </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="pd-20 card-box mb-30">

            <!-- Selected Contacts -->
            <h5 class="card-title">Selected Contacts</h5>
            <div class="pb-20 mt-5">
                <table class="data-table table stripe hover nowrap">
                    <thead>
                        <tr>
                            <th>User ID</th>
                            <th>User Name</th>
                            <th>User Email</th>
                            <th>User Permission</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach (($recipients ?? []) as $user): ?>
                                <tr>
                                    <td><?= htmlspecialchars($user['ENTITY_ID']) ?></td>
                                    <td><?= htmlspecialchars($user['NAME'] ?? $user['user_name'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($user['EMAIL'] ?? $user['email_id'] ?? '') ?></td>
                                    <td><?= htmlspecialchars(campaign_audience_label($campaign_data['MODULE_NAME'])) ?></td>
                                </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="pd-20 card-box mb-30">
            <!-- Campaign Dates -->
            <div class="form-group row">
                <label class="col-sm-12 col-md-2 col-form-label">Start Date</label>
                <div class="col-sm-12 col-md-10">
                    <p class="form-control-static"><?= $campaign_data['START_DATE'] ?></p>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-12 col-md-2 col-form-label">End Date</label>
                <div class="col-sm-12 col-md-10">
                    <p class="form-control-static"><?= $campaign_data['END_DATE'] ?></p>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<?php $this->load->view('includes/footer'); ?>
<script src="<?= base_url('src/plugins/datatables/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?= base_url('src/plugins/datatables/js/dataTables.bootstrap4.min.js'); ?>"></script>
<script src="<?= base_url('src/plugins/datatables/js/dataTables.responsive.min.js'); ?>"></script>
<script src="<?= base_url('src/plugins/datatables/js/responsive.bootstrap4.min.js'); ?>"></script>
<script>
    $(document).ready(function() {
        $('.data-table').DataTable({
            "pageLength": 10,
            "responsive": true,
            "autoWidth": false
        });
    });
</script>
</body>

</html>