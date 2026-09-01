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
                        <h4>Add A New Client Task</h4>
                    </div>
                </div>
                <div class="col-md-6 col-sm-12 text-right">
                    <a class="btn btn-warning" href="<?= base_url('/clients'); ?>" role="button">
                        Back To Client List
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
            <form action="<?= base_url('ClientController/createTask/'. $ENTITY_ID) ?>" method="post">
                <input type="hidden" name="ENTITY_ID" value="<?= $ENTITY_ID ?>">
                <input type="hidden" name="ASSIGN_TO" value="<?= $ENTITY_DATA['LEAD_OWNER'] ?>">


                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Assign To <span style="color: red;">*</span></label>
                            <div class="col-md-8">
                                <select name="ASSIGN_TO" id="ASSIGN_TO" class="form-control" onchange="" >
                                    <option value="-1">Select</option>
                                    <?php
                                    foreach ($all_users as $user) { ?>
                                        <option <?= $ENTITY_DATA['LEAD_OWNER'] == $user['ENTITY_ID'] ? 'selected' : '' ?> value="<?= $user['ENTITY_ID'] ?>"><?= $user['NAME'] ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Status <span style="color: red;">*</span></label>
                            <div class="col-md-8">
                                <select name="STATUS" id="STATUS" class="form-control" onchange="">
                                    <option value="-1">Select</option>
                                    <option value="1">Not Started</option>
                                    <option value="2">Work in Progress</option>
                                    <option value="3">Completed</option>
                                    <option value="4">Waiting for Some One</option>
                                    <option value="5">Deferred</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Task Type</label>
                            <div class="col-md-8">
                                <select name="TASK_TYPE" id="TASK_TYPE" class="form-control" onchange="">
                                    <option value="-1">Select</option>
                                    <option value="66654680">Call</option>
                                    <option value="91753743">Message</option>
                                    <option value="70091779">Email</option>
                                    <option value="8152976">Contact</option>
                                    <option value="91879879">Social Media</option>
                                    <option value="83535584">Campaign</option>
                                    <option value="17262660">Template</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-form-label col-md-2">Subject <span style="color: red;">*</span></label>
                    <div class="col-md-10">
                        <input class="form-control" type="text" name="SUBJECT" placeholder="Subject" />
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Priority</label>
                            <div class="col-md-8">
                                <select name="PRIORITY" id="PRIORITY" class="form-control" onchange="">
                                    <option value="-1">Select</option>
                                    <option value="1">Low</option>
                                    <option value="2">Medium</option>
                                    <option value="3">High</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Start Date<span style="color: red;">*</span></label>
                            <div class="col-md-8">
                                <input class="form-control" type="datetime-local" name="START_DATE" placeholder="Start date" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Start Time<span style="color: red;">*</span></label>
                            <div class="col-md-8">
                                <select name="START_TIME" id="START_TIME" class="form-control" onchange="">
                                    <option value="-1">Select</option>
                                    <?php
                                    for ($i = 0; $i < 96; $i++) {
                                        $hour = floor($i / 4);
                                        $minute = ($i % 4) * 15;
                                        $ampm = $hour < 12 ? 'AM' : 'PM';
                                        $hour = $hour % 12;
                                        $hour = $hour == 0 ? 12 : $hour;
                                        $time = sprintf("%02d:%02d %s", $hour, $minute, $ampm);
                                        echo "<option value=\"$i\">$time</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">End Time<span style="color: red;">*</span></label>
                            <div class="col-md-8">
                                <select name="END_TIME" id="END_TIME" class="form-control" onchange="">
                                    <option value="-1">Select</option>
                                    <?php
                                    for ($i = 0; $i < 96; $i++) {
                                        $hour = floor($i / 4);
                                        $minute = ($i % 4) * 15;
                                        $ampm = $hour < 12 ? 'AM' : 'PM';
                                        $hour = $hour % 12;
                                        $hour = $hour == 0 ? 12 : $hour;
                                        $time = sprintf("%02d:%02d %s", $hour, $minute, $ampm);
                                        echo "<option value=\"$i\">$time</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-form-label col-md-2">Description </label>
                    <div class="col-md-10">
                        <input class="form-control" type="text" name="TASK_DESC" placeholder="Description" />
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-6 offset-md-2">
                        <button type="submit" class="btn btn-warning">Add Task</button>
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