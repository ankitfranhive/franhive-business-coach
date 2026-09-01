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
                        <h4>Edit Lead Status</h4>
                    </div>
                </div>
                <div class="col-md-6 col-sm-12 text-right">
                    <a class="btn btn-warning" href="<?= base_url('/leads'); ?>" role="button">
                        Back To Lead List
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
            <form action="<?= base_url('leadController/changeStatus/' . $ENTITY_DATA['ENTITY_ID']) ?>" method="post">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Lead Status <span style="color: red;">*</span></label>
                            <div class="col-md-8">
                                <!-- Lead Status -->
                                <select name="LEAD_STATUS" id="ENTITY_LEAD_STATUS" class="form-control">
                                    <option value="-1">Select</option>
                                    <option value="1" <?= ($ENTITY_DATA['LEAD_STATUS'] == 1) ? 'selected' : '' ?>>New</option>
                                    <option value="2" <?= ($ENTITY_DATA['LEAD_STATUS'] == 2) ? 'selected' : '' ?>>Closed</option>
                                    <option value="3" <?= ($ENTITY_DATA['LEAD_STATUS'] == 3) ? 'selected' : '' ?>>Red Flag</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-6 offset-md-2">
                        <button type="submit" class="btn btn-warning">Update Lead Status</button>
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