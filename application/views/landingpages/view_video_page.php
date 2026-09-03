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
                        <h4>View Video Details</h4>
                    </div>
                </div>
                <div class="col-md-6 col-sm-12 text-right">
                    <a class="btn btn-warning" href="<?= base_url('LandingPageController/videoList/' . $videos_data['LANDING_PAGE_ID'] . ''); ?>" role="button">
                        Back To Pages List
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
                        <label class="col-form-label col-md-4">Title: </label>
                        <div class="col-md-8">
                            <p class="form-control-static">
                                <?= $videos_data['VIDEO_TITLE'] ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-form-label col-md-4">Embed Videos (URL): </label>
                        <div class="col-md-8">
                            <p class="form-control-static">
                                <?= $videos_data['VIDEO_TEXT'] ?>
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