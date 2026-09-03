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
                        <h4>View Landing Page Details</h4>
                    </div>
                </div>
                <div class="col-md-6 col-sm-12 text-right">
                    <a class="btn btn-warning" href="<?= base_url('/landing-pages'); ?>" role="button">
                        Back To Pages List
                    </a>
                    <a class="btn btn-warning" href="<?php echo base_url('videos-list/' . $landing_page['LANDING_PAGE_ID'] . ''); ?>" role="button">
                        Videos
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
            <h5>Landing Page Details</h5>
            <br>
            <input type="hidden" name="id" value="<?= $landing_page['LANDING_PAGE_ID'] ?>">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-form-label col-md-4">Landing Page Title: <span style="color: red;">*</span></label>
                        <div class="col-md-8">
                            <p class="form-control-static">
                                <?= $landing_page['LANDING_PAGE_NAME'] ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-form-label col-md-4">Landing Page URL: <span style="color: red;">*</span></label>
                        <div class="col-md-8">
                            <p class="form-control-static">
                                <?= $landing_page['LANDING_PAGE_URL'] ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-form-label col-md-4">Banner Image: <span style="color: red;">*</span></label>
                        <div class="col-md-8">
                            <p class="form-control-static">
                                <img src="<?= $landing_page['BANNER_IMAGE'] ?>" alt="Banner Image" style="width: 100%; height: 350px;">
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-form-label col-md-4">Email: <span style="color: red;">*</span></label>
                        <div class="col-md-8">
                            <p class="form-control-static">
                                <?= $landing_page['CC_EMAIL'] ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-form-label col-md-4">Email Template on Registration: <span style="color: red;">*</span></label>
                        <div class="col-md-8">
                            <p class="form-control-static">
                                <?= $landing_page['email_template_name'] ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-form-label col-md-4">Email Template on Payment: <span style="color: red;">*</span></label>
                        <div class="col-md-8">
                            <p class="form-control-static">
                                <?= $landing_page['payment_template_name'] ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-form-label col-md-4">Landing Page Main Content: <span style="color: red;">*</span></label>
                        <div class="col-md-8">
                            <p class="form-control-static">
                                <?= $landing_page['LANDING_PAGE_LEFT_CONTENT'] ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-form-label col-md-4">Landing Page Side Content : <span style="color: red;">*</span></label>
                        <div class="col-md-8">
                            <p class="form-control-static">
                                <?= $landing_page['LANDING_PAGE_RIGHT_CONTENT'] ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-form-label col-md-4">Landing Page VIP Experience Content (Before Link) : <span style="color: red;">*</span></label>
                        <div class="col-md-8">
                            <p class="form-control-static">
                                <?= $landing_page['LANDING_PAGE_VIP_EXPERIENCE_CONTENT'] ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-form-label col-md-4">Landing Page VIP Experience Content Steps : <span style="color: red;">*</span></label>
                        <div class="col-md-8">
                            <p class="form-control-static">
                                <?= $landing_page['LANDING_PAGE_VIP_EXPERIENCE_CONTENT_STEPS'] ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-form-label col-md-4">Landing Page Thanks Content: <span style="color: red;">*</span></label>
                        <div class="col-md-8">
                            <p class="form-control-static">
                                <?= $landing_page['LANDING_PAGE_THANKS_CONTENT'] ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-form-label col-md-4">Fee: <span style="color: red;">*</span></label>
                        <div class="col-md-8">
                            <p class="form-control-static">
                                <?= $landing_page['LANDING_PAGE_FEE'] ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-form-label col-md-4">VIP Fee: <span style="color: red;">*</span></label>
                        <div class="col-md-8">
                            <p class="form-control-static">
                                <?= $landing_page['LANDING_PAGE_VIP_FEE'] ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php $this->load->view('includes/footer'); ?>

</html>