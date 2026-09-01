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
                        <h4>Send Email</h4>
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

            <form action="<?= base_url('LeadController/sendLeadEmail') ?>" method="post">

                <input type="hidden" name="ENTITY_ID" value="<?= $user['ENTITY_ID'] ?>">
                <input type="hidden" name="LEAD_EMAIL" value="<?= $user['EMAIL'] ?>">
                <input type="hidden" name="LEAD_NAME" value="<?= $user['NAME'] ?>">


                <div class="form-group row">
                    <label class="col-form-label col-md-2">Lead Email </label>
                    <div class="col-md-10">
                        <input class="form-control" type="text" name="LEAD_EMAIL" placeholder="LEAD_EMAIL" value="<?= $user['EMAIL'] ?>" disabled />
                    </div>
                </div>


                <div class="form-group row">
                    <label class="col-form-label col-md-2">Email Template <span style="color: red;">*</span></label>
                    <div class="col-md-10">
                        <select name="EMAIL_TEMPLATE" id="EMAIL_TEMPLATE" class="form-control" onchange="">
                            <option value="-1">Select</option>
                            <?php
                            foreach ($templates as $template) { ?>
                                <option value="<?= $template['TEMPLATE_ID'] ?>"><?= $template['TEMPLATE_NAME'] ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-6 offset-md-2">
                        <button type="submit" class="btn btn-warning">Send Email</button>
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