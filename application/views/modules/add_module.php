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
                        <h4>Add A new Module</h4>
                    </div>
                </div>
                <div class="col-md-6 col-sm-12 text-right">
                    <a
                        class="btn btn-warning"
                        href="<?= base_url('/modules'); ?>"
                        role="button"
                    >
                        Back To Modules List
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
            <form action="<?= base_url('add-module') ?>" method="post">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Module Name <span style="color: red;">*</span></label>
                            <div class="col-md-8">
                                <input
                                    class="form-control"
                                    type="text"
                                    name="module_name"
                                    placeholder="Module Name"
                                />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-6 offset-md-2">
                        <button type="submit" class="btn btn-warning">Add Module</button>
                    </div>
                </div>
            </form>
        </div>
        <!-- Default Basic Forms End -->
    </div>
</div>
<?php $this->load->view('includes/footer'); ?>

</html>
