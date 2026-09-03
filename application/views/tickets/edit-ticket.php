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
                        <h4>Edit Ticket</h4>
                    </div>
                </div>
                <div class="col-md-6 col-sm-12 text-right">
                    <a class="btn btn-warning" href="<?= base_url('/ticket-list'); ?>" role="button">
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

            <form action="<?= base_url('TicketController/updateTicket/' . $ticket_data['TICKET_ID']) ?>" method="post" enctype="multipart/form-data">
                <input type="hidden" name="TICKET_ID" value="<?= $ticket_data['TICKET_ID'] ?>">

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Department <span style="color: red;">*</span></label>
                            <div class="col-md-8">
                                <select name="DEPARTMENT_ID" id="DEPARTMENT_ID" class="form-control" onchange="" required>
                                    <option value="-1">Select</option>
                                    <?php foreach ($departments_list as $department) : ?>

                                        <option <?= $department['TICKET_DEPARTMENT_ID'] ==  $ticket_data['DEPARTMENT'] ? 'selected' : '' ?> value="<?php echo $department['TICKET_DEPARTMENT_ID']; ?>"><?php echo $department['DEPARTMENT_NAME']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Priority <span style="color: red;">*</span></label>
                            <div class="col-md-8">
                                <select name="PRIORITY" id="PRIORITY" class="form-control" onchange="" required>
                                    <option value="-1">Select</option>
                                    <?php foreach ($priority_list as $priority) : ?>
                                        <option <?= $priority['TICKET_PRIORITY_ID'] ==  $ticket_data['PRIORITY'] ? 'selected' : '' ?> value="<?php echo $priority['TICKET_PRIORITY_ID']; ?>"><?php echo $priority['PRIORITY_NAME']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Client Name <span style="color: red;">*</span></label>
                            <div class="col-md-8">
                                <select name="STORE_ID" id="STORE_ID" class="form-control" onchange="" required>
                                    <option value="-1">Select</option>
                                    <?php foreach ($user_list as $user) : ?>
                                        <option <?= $user['USER_ID'] ==  $ticket_data['STORE_ID'] ? 'selected' : '' ?> value="<?php echo $user['USER_ID']; ?>"><?php echo $user['NAME']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Status <span style="color: red;">*</span></label>
                            <div class="col-md-8">
                                <select name="TICKET_STATUS" id="TICKET_STATUS" class="form-control" onchange="" required>
                                    <option value="-1">Select</option>
                                    <?php foreach ($status_list as $status) : ?>
                                        <option <?= $status['TICKET_STATUS_ID'] ==  $ticket_data['TICKET_STATUS'] ? 'selected' : '' ?> value="<?php echo $status['TICKET_STATUS_ID']; ?>"><?php echo $status['STATUS']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-form-label col-md-2">Subject <span style="color: red;">*</span></label>
                            <div class="col-md-10">
                                <input class="form-control" type="text" name="SUBJECT" placeholder="Subject" required value="<?= $ticket_data['SUBJECT'] ?>" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-form-label col-md-2">Description <span style="color: red;">*</span></label>
                            <div class="col-md-10">
                                <input class="form-control" value="<?= $ticket_data['DESCRIPTION'] ?>" type="text" name="DESCRIPTION" placeholder="Description" required />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Attachment <span style="color: red;">*</span></label>
                            <div class="col-md-8">
                                <input class="form-control" value="" name="ATTACHMENT" type="file" />
                                <a href="<?= $ticket_data['ATTACHMENT']  ?>" target="_blank">View File</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-6 offset-md-2">
                        <button type="submit" class="btn btn-warning">Update Ticket</button>
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