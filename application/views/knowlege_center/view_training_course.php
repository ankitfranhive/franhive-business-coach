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
                        <h4>View Course Details</h4>
                    </div>
                </div>
                <div class="col-md-6 col-sm-12 text-right">
                    <a class="btn btn-warning" href="<?= base_url('/knowledge-center'); ?>" role="button">
                        Back To Course List
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
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-form-label col-md-4">Name <span style="color: red;">*</span></label>
                        <div class="col-md-8">
                            <p class="form-control-static"><?= $course_data['NAME'] ?></p>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-form-label col-md-4">Objective <span style="color: red;">*</span></label>
                        <div class="col-md-8">
                            <p class="form-control-static"><?= $course_data['OBJECTIVE'] ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-form-label col-md-4">Cover Image <span style="color: red;">*</span></label>
                        <div class="col-md-8">
                            <p class="form-control-static">
                                <a href="<?= $course_data['THUMNAIL_IMAGE'] ?>" target="_blank">Cover Image</a>
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<?php $this->load->view('includes/footer'); ?>

</html>