<!DOCTYPE html>
<html>
<?php $this->load->view('includes/header'); ?>


<?php

function generateTimeOptions($selectedValue = -1)
{
    $times = [];
    $time = strtotime('00:00');

    for ($i = 0; $i < 96; $i++) {
        $times[] = date('h:i A', $time);
        $time = strtotime('+15 minutes', $time);
    }

    $options = '<option value="-1">Select</option>';
    foreach ($times as $index => $time) {
        $selected = ($index == $selectedValue) ? ' selected' : '';
        $options .= '<option value="' . $index . '"' . $selected . '>' . $time . '</option>';
    }

    return $options;
}


?>
<div class="mobile-menu-overlay"></div>
<div class="main-container">
    <div class="pd-ltr-20 xs-pd-20-10">
        <div class="page-header">
            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <div class="title">
                        <h4>Edit Task</h4>
                    </div>
                </div>
                <div class="col-md-6 col-sm-12 text-right">
                    <a class="btn btn-warning" href="<?= base_url('leadController/getAllTasks'); ?>" role="button">
                        Back To Task List
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

            <form action="<?= base_url('leadController/updateTask') ?>" method="post">
                <input type="hidden" name="ENTITY_ID" value="<?= $task_data['ENTITY_ID'] ?>">
                <input type="hidden" name="TASK_ID" value="<?= $task_data['TASK_ID'] ?>">
                <input type="hidden" name="ASSIGN_TO" value="<?= $task_data['ASSIGN_TO'] ?>">



                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Assign To <span style="color: red;">*</span></label>

                            <div class="col-md-8">
                                <select name="ASSIGN_TO" id="ASSIGN_TO" class="form-control" onchange="" <?= $user_role != 'ADMIN' ? 'disabled' : '' ?>>
                                    <option value="-1" <?= $task_data['ASSIGN_TO'] == '-1' ? 'selected' : '' ?>>Select</option>
                                    <?php
                                    foreach ($all_users as $user) { ?>
                                        <option value="<?= $user['ENTITY_ID'] ?>" <?= $task_data['ASSIGN_TO'] == $user['ENTITY_ID'] ? 'selected' : '' ?>><?= $user['NAME'] ?></option>
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
                                    <option value="-1" <?= ($task_data['STATUS'] == -1) ? 'selected' : '' ?>>Select</option>
                                    <option value="1" <?= ($task_data['STATUS'] == 1) ? 'selected' : '' ?>>Not Started</option>
                                    <option value="2" <?= ($task_data['STATUS'] == 2) ? 'selected' : '' ?>>Work in Progress</option>
                                    <option value="3" <?= ($task_data['STATUS'] == 3) ? 'selected' : '' ?>>Completed</option>
                                    <option value="4" <?= ($task_data['STATUS'] == 4) ? 'selected' : '' ?>>Waiting for Some One</option>
                                    <option value="5" <?= ($task_data['STATUS'] == 5) ? 'selected' : '' ?>>Deferred</option>
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
                                <select name="TASK_TYPE" id="TASK_TYPE" class="form-control" onchange="" <?= $user_role != 'ADMIN' ? 'disabled' : '' ?>>
                                    <option value="-1" <?= $task_data['TASK_TYPE'] == -1 ? 'selected' : '' ?>>Select</option>
                                    <option value="66654680" <?= $task_data['TASK_TYPE'] == 66654680 ? 'selected' : '' ?>>Call</option>
                                    <option value="91753743" <?= $task_data['TASK_TYPE'] == 91753743 ? 'selected' : '' ?>>Message</option>
                                    <option value="70091779" <?= $task_data['TASK_TYPE'] == 70091779 ? 'selected' : '' ?>>Email</option>
                                    <option value="8152976" <?= $task_data['TASK_TYPE'] == 8152976 ? 'selected' : '' ?>>Contact</option>
                                    <option value="91879879" <?= $task_data['TASK_TYPE'] == 91879879 ? 'selected' : '' ?>>Social Media</option>
                                    <option value="83535584" <?= $task_data['TASK_TYPE'] == 83535584 ? 'selected' : '' ?>>Campaign</option>
                                    <option value="17262660" <?= $task_data['TASK_TYPE'] == 17262660 ? 'selected' : '' ?>>Template</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-form-label col-md-2">Subject <span style="color: red;">*</span></label>
                    <div class="col-md-10">
                        <input class="form-control" type="text" name="SUBJECT" placeholder="Subject" value="<?= $task_data['SUBJECT'] ?>" <?= $user_role != 'ADMIN' ? 'disabled' : '' ?> />
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Priority</label>
                            <div class="col-md-8">
                                <select name="PRIORITY" id="PRIORITY" class="form-control" onchange="" <?= $user_role != 'ADMIN' ? 'disabled' : '' ?>>
                                    <option value="-1" <?= ($task_data['PRIORITY'] == -1) ? 'selected' : '' ?>>Select</option>
                                    <option value="1" <?= ($task_data['PRIORITY'] == 1) ? 'selected' : '' ?>>Low</option>
                                    <option value="2" <?= ($task_data['PRIORITY'] == 2) ? 'selected' : '' ?>>Medium</option>
                                    <option value="3" <?= ($task_data['PRIORITY'] == 3) ? 'selected' : '' ?>>High</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Start Date<span style="color: red;">*</span></label>
                            <div class="col-md-8">
                                <input class="form-control" type="datetime-local" name="START_DATE" placeholder="Start date" value="<?= $task_data['START_DATE'] ?>" <?= $user_role != 'ADMIN' ? 'disabled' : '' ?> />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Start Time<span style="color: red;">*</span></label>
                            <div class="col-md-8">
                                <select name="START_TIME" id="START_TIME" class="form-control" onchange="" <?= $user_role != 'ADMIN' ? 'disabled' : '' ?>>
                                    <?= generateTimeOptions($task_data['START_TIME']) ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">End Time<span style="color: red;">*</span></label>
                            <div class="col-md-8">
                                <select name="END_TIME" id="END_TIME" class="form-control" onchange="" <?= $user_role != 'ADMIN' ? 'disabled' : '' ?>>
                                    <?= generateTimeOptions($task_data['END_TIME']) ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-form-label col-md-2">Description </label>
                    <div class="col-md-10">
                        <input class="form-control" type="text" name="TASK_DESC" placeholder="Description" value="<?= $task_data['TASK_DESC'] ?>" />
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-6 offset-md-2">
                        <button type="submit" class="btn btn-warning">Update Task</button>
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