<!DOCTYPE html>
<html>
<?php $this->load->view('includes/header'); ?>

<div class="mobile-menu-overlay"></div>

<div class="main-container">
    <div class="pd-ltr-20 xs-pd-20-10">
        <div class="page-header">
            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <div class="title">
                        <h4>View Template: [<?= htmlspecialchars($template_data['TEMPLATE_NAME']) ?>]</h4>
                    </div>
                </div>
                <div class="col-md-6 col-sm-12 text-right">
                    <a class="btn btn-warning" href="<?= base_url('/templates'); ?>" role="button">
                        Back To Templates List
                    </a>
                </div>
            </div>
        </div>
        <div class="pd-20 card-box mb-30">
            <div class="form-group row">
                <label class="col-sm-12 col-md-2 col-form-label">Create Template for</label>
                <div class="col-sm-12 col-md-10">
                    <p class="form-control-static">
                        <?= htmlspecialchars($template_data['MODULE_NAME'] == '1' ? 'Client' : 'Lead') ?>
                    </p>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-12 col-md-2 col-form-label">Template Name <span style="color: red;">*</span></label>
                <div class="col-sm-12 col-md-10">
                    <p class="form-control-static">
                        <?= htmlspecialchars($template_data['TEMPLATE_NAME']) ?>
                    </p>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-12 col-md-2 col-form-label">Template Subject <span style="color: red;">*</span></label>
                <div class="col-sm-12 col-md-10">
                    <p class="form-control-static">
                        <?= htmlspecialchars($template_data['TEMPLATE_SUBJECT']) ?>
                    </p>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-12 col-md-2 col-form-label">Email Content <span style="color: red;">*</span></label>
                <div class="col-sm-12 col-md-10">
                    <div class="row">
                        <div class="col-md-12">
                            <!-- Use HTML content rendering for TEMPLATE_BODY -->
                            <div class="form-group">
                                <?= htmlspecialchars_decode($template_data['TEMPLATE_BODY']) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-12 col-md-2 col-form-label">Signature <span style="color: red;">*</span></label>
                <div class="col-sm-12 col-md-10">
                    <p class="form-control-static">
                        <?= htmlspecialchars($template_data['TEMPLATE_SIGN'] == "1" ? 'Barinderjeet' : '') ?>
                    </p>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-12 col-md-2 col-form-label">Attachments</label>
                <div class="col-sm-12 col-md-10">
                    <div class="row">
                        <div class="col-md-12">
                            <?php if (!empty($template_data['ATTACHMENTS'])): ?>

                                <?php foreach (explode(',', $template_data['ATTACHMENTS']) as $attachment): ?>
                                    <div>
                                        <a href="<?= htmlspecialchars($attachment) ?>" target="_blank"><?= htmlspecialchars(basename($attachment)) ?></a>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="form-control-static">No attachments.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('includes/footer'); ?>

<!-- Load CKEditor if needed -->
<script src="<?php echo base_url('assets/ckeditor/ckeditor.js'); ?>"></script>
<script type="text/javascript">
    $(function() {
        CKEDITOR.replace('ckeditor', {
            filebrowserImageBrowseUrl: '<?php echo base_url('assets/kcfinder/browse.php'); ?>',
            height: '400px'
        });
    });
</script>

</body>

</html>