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
                        <h4>Edit Web Form</h4>
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

            <form action="<?= base_url('/edit-web-form/' . $web_form_data['FORM_ID'] . '') ?>" method="post" enctype="multipart/form-data">
                <input type="hidden" name="FORM_ID" value="<?= $web_form_data['FORM_ID'] ?>">

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Form Name <span style="color: red;">*</span></label>
                            <div class="col-md-8">
                                <input class="form-control" type="text" name="WEB_FORM_NAME" placeholder="Web Form Name" value="<?= $web_form_data['WEB_FORM_NAME'] ?>" />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Title <span style="color: red;">*</span></label>
                            <div class="col-md-8">
                                <input class="form-control" type="text" name="WEB_FORM_TITLE" placeholder="Web Form Title" value="<?= $web_form_data['WEB_FORM_TITLE'] ?>" />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4" for="TEMPLETE_FILE">Template File <span style="color: red;">*</span></label>
                            <input class="form-control" value="<?= $web_form_data['WEB_FORM_FILE'] ?>" name="TEMPLETE_FILE" type="file" />

                            <div class="col-md-8">
                                <p class="form-control-static">

                                    <a href="<?= $web_form_data['WEB_FORM_FILE'] ?>" target="_blank">View uploded file</a>
                                </p>
                            </div>
                        </div>
                    </div>


                </div>
                <div class="form-group row">
                    <div class="col-md-6 offset-md-2">
                        <button type="submit" class="btn btn-warning">Update web form</button>
                    </div>
                </div>
            </form>
        </div>
        <!-- Default Basic Forms End -->
    </div>
</div>
</body>
<?php $this->load->view('includes/footer'); ?>

</html>