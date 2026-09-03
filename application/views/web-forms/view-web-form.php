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
                        <h4>View Web Form</h4>
                    </div>
                </div>
                <div class="col-md-6 col-sm-12 text-right">
                    <a class="btn btn-warning" href="<?= base_url('/web-forms-list'); ?>" role="button">
                        Back To List
                    </a>
                </div>
            </div>
        </div>
        <div class="pd-20 card-box mb-30">
            <div class="clearfix">
                <div class="pull-left">
                    <!-- <h4 class="text-blue h4">Add A new Question</h4> -->
                </div>
            </div>



            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-form-label col-md-4">Form Name <span style="color: red;">*</span></label>
                        <div class="col-md-8">
                            <p class="form-control-static"><?= $web_form_data['WEB_FORM_NAME'] ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-form-label col-md-4">Title <span style="color: red;">*</span></label>
                        <div class="col-md-8">
                            <p class="form-control-static">
                                <?= $web_form_data['WEB_FORM_TITLE'] ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-form-label col-md-4" for="TEMPLETE_FILE">Template File <span style="color: red;">*</span></label>
                        <div class="col-md-8">
                            <p class="form-control-static">

                                <a href="<?= $web_form_data['WEB_FORM_FILE'] ?>" target="_blank">View uploded file</a>
                            </p>
                        </div>
                    </div>
                </div>


            </div>

        </div>
        <!-- Default Basic Forms End -->
    </div>
</div>
</body>
<?php $this->load->view('includes/footer'); ?>

</html>