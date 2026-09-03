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
                        <h4>Add A new Lead Task</h4>
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
            <form action="<?= base_url('leadController/createTask') ?>" method="post">
                <input type="hidden" name="ENTITY_ID" value="<?= $ENTITY_ID ?>">
                <input type="hidden" name="ASSIGN_TO" value="<?= isset($ENTITY_DATA['LEAD_OWNER']) ? $ENTITY_DATA['LEAD_OWNER'] : 0 ?>">



                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Assign To <span style="color: red;">*</span></label>
                            <div class="col-md-8">
                                <select name="ASSIGN_TO" id="ASSIGN_TO" class="form-control" onchange="" >
                                    <option value="-1">Select</option>
                                    <?php
                                    // echo "<pre>";
                                    // print_r($all_users);
                                    foreach ($all_users as $user) { ?>
                                       <option 
                                        <?= (isset($ENTITY_DATA['LEAD_OWNER']) && $ENTITY_DATA['LEAD_OWNER'] == $user['ENTITY_ID']) ? 'selected' : '' ?> 
                                        value="<?= $user['ENTITY_ID'] ?>">
                                        <?= $user['NAME'] ?>
                                    </option>

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
                                    <option value="0">12:00 AM</option>
                                    <option value="1">12:15 AM</option>
                                    <option value="2">12:30 AM</option>
                                    <option value="3">12:45 AM</option>
                                    <option value="4">1:00 AM</option>
                                    <option value="5">1:15 AM</option>
                                    <option value="6">1:30 AM</option>
                                    <option value="7">1:45 AM</option>
                                    <option value="8">2:00 AM</option>
                                    <option value="9">2:15 AM</option>
                                    <option value="10">2:30 AM</option>
                                    <option value="11">2:45 AM</option>
                                    <option value="12">3:00 AM</option>
                                    <option value="13">3:15 AM</option>
                                    <option value="14">3:30 AM</option>
                                    <option value="15">3:45 AM</option>
                                    <option value="16">4:00 AM</option>
                                    <option value="17">4:15 AM</option>
                                    <option value="18">4:30 AM</option>
                                    <option value="19">4:45 AM</option>
                                    <option value="10">5:00 AM</option>
                                    <option value="21">5:15 AM</option>
                                    <option value="22">5:30 AM</option>
                                    <option value="23">5:45 AM</option>
                                    <option value="24">6:00 AM</option>
                                    <option value="25">6:15 AM</option>
                                    <option value="26">6:30 AM</option>
                                    <option value="27">6:45 AM</option>
                                    <option value="28">7:00 AM</option>
                                    <option value="29">7:15 AM</option>
                                    <option value="30">7:30 AM</option>
                                    <option value="31">7:45 AM</option>
                                    <option value="32">8:00 AM</option>
                                    <option value="33">8:15 AM</option>
                                    <option value="34">8:30 AM</option>
                                    <option value="35">8:45 AM</option>
                                    <option value="36">9:00 AM</option>
                                    <option value="37">9:15 AM</option>
                                    <option value="38">9:30 AM</option>
                                    <option value="39">9:45 AM</option>
                                    <option value="40">10:00 AM</option>
                                    <option value="41">10:15 AM</option>
                                    <option value="42">10:30 AM</option>
                                    <option value="43">10:45 AM</option>
                                    <option value="44">11:00 AM</option>
                                    <option value="45">11:15 AM</option>
                                    <option value="46">11:30 AM</option>
                                    <option value="47">11:45 AM</option>
                                    <option value="48">12:00 PM</option>
                                    <option value="49">12:15 PM</option>
                                    <option value="50">12:30 PM</option>
                                    <option value="51">12:45 PM</option>
                                    <option value="52">1:00 PM</option>
                                    <option value="53">1:15 PM</option>
                                    <option value="54">1:30 PM</option>
                                    <option value="55">1:45 PM</option>
                                    <option value="56">2:00 PM</option>
                                    <option value="57">2:15 PM</option>
                                    <option value="58">2:30 PM</option>
                                    <option value="59">2:45 PM</option>
                                    <option value="60">3:00 PM</option>
                                    <option value="61">3:15 PM</option>
                                    <option value="62">3:30 PM</option>
                                    <option value="63">3:45 PM</option>
                                    <option value="64">4:00 PM</option>
                                    <option value="65">4:15 PM</option>
                                    <option value="66">4:30 PM</option>
                                    <option value="67">4:45 PM</option>
                                    <option value="68">5:00 PM</option>
                                    <option value="69">5:15 PM</option>
                                    <option value="70">5:30 PM</option>
                                    <option value="71">5:45 PM</option>
                                    <option value="72">6:00 PM</option>
                                    <option value="73">6:15 PM</option>
                                    <option value="74">6:30 PM</option>
                                    <option value="75">6:45 PM</option>
                                    <option value="76">7:00 PM</option>
                                    <option value="77">7:15 PM</option>
                                    <option value="78">7:30 PM</option>
                                    <option value="79">7:45 PM</option>
                                    <option value="80">8:00 PM</option>
                                    <option value="81">8:15 PM</option>
                                    <option value="82">8:30 PM</option>
                                    <option value="83">8:45 PM</option>
                                    <option value="84">9:00 PM</option>
                                    <option value="85">9:15 PM</option>
                                    <option value="86">9:30 PM</option>
                                    <option value="87">9:45 PM</option>
                                    <option value="88">10:00 PM</option>
                                    <option value="89">10:15 PM</option>
                                    <option value="90">10:30 PM</option>
                                    <option value="91">10:45 PM</option>
                                    <option value="92">11:00 PM</option>
                                    <option value="93">11:15 PM</option>
                                    <option value="94">11:30 PM</option>
                                    <option value="95">11:45 PM</option>
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
                                    <option value="0">12:00 AM</option>
                                    <option value="1">12:15 AM</option>
                                    <option value="2">12:30 AM</option>
                                    <option value="3">12:45 AM</option>
                                    <option value="4">1:00 AM</option>
                                    <option value="5">1:15 AM</option>
                                    <option value="6">1:30 AM</option>
                                    <option value="7">1:45 AM</option>
                                    <option value="8">2:00 AM</option>
                                    <option value="9">2:15 AM</option>
                                    <option value="10">2:30 AM</option>
                                    <option value="11">2:45 AM</option>
                                    <option value="12">3:00 AM</option>
                                    <option value="13">3:15 AM</option>
                                    <option value="14">3:30 AM</option>
                                    <option value="15">3:45 AM</option>
                                    <option value="16">4:00 AM</option>
                                    <option value="17">4:15 AM</option>
                                    <option value="18">4:30 AM</option>
                                    <option value="19">4:45 AM</option>
                                    <option value="10">5:00 AM</option>
                                    <option value="21">5:15 AM</option>
                                    <option value="22">5:30 AM</option>
                                    <option value="23">5:45 AM</option>
                                    <option value="24">6:00 AM</option>
                                    <option value="25">6:15 AM</option>
                                    <option value="26">6:30 AM</option>
                                    <option value="27">6:45 AM</option>
                                    <option value="28">7:00 AM</option>
                                    <option value="29">7:15 AM</option>
                                    <option value="30">7:30 AM</option>
                                    <option value="31">7:45 AM</option>
                                    <option value="32">8:00 AM</option>
                                    <option value="33">8:15 AM</option>
                                    <option value="34">8:30 AM</option>
                                    <option value="35">8:45 AM</option>
                                    <option value="36">9:00 AM</option>
                                    <option value="37">9:15 AM</option>
                                    <option value="38">9:30 AM</option>
                                    <option value="39">9:45 AM</option>
                                    <option value="40">10:00 AM</option>
                                    <option value="41">10:15 AM</option>
                                    <option value="42">10:30 AM</option>
                                    <option value="43">10:45 AM</option>
                                    <option value="44">11:00 AM</option>
                                    <option value="45">11:15 AM</option>
                                    <option value="46">11:30 AM</option>
                                    <option value="47">11:45 AM</option>
                                    <option value="48">12:00 PM</option>
                                    <option value="49">12:15 PM</option>
                                    <option value="50">12:30 PM</option>
                                    <option value="51">12:45 PM</option>
                                    <option value="52">1:00 PM</option>
                                    <option value="53">1:15 PM</option>
                                    <option value="54">1:30 PM</option>
                                    <option value="55">1:45 PM</option>
                                    <option value="56">2:00 PM</option>
                                    <option value="57">2:15 PM</option>
                                    <option value="58">2:30 PM</option>
                                    <option value="59">2:45 PM</option>
                                    <option value="60">3:00 PM</option>
                                    <option value="61">3:15 PM</option>
                                    <option value="62">3:30 PM</option>
                                    <option value="63">3:45 PM</option>
                                    <option value="64">4:00 PM</option>
                                    <option value="65">4:15 PM</option>
                                    <option value="66">4:30 PM</option>
                                    <option value="67">4:45 PM</option>
                                    <option value="68">5:00 PM</option>
                                    <option value="69">5:15 PM</option>
                                    <option value="70">5:30 PM</option>
                                    <option value="71">5:45 PM</option>
                                    <option value="72">6:00 PM</option>
                                    <option value="73">6:15 PM</option>
                                    <option value="74">6:30 PM</option>
                                    <option value="75">6:45 PM</option>
                                    <option value="76">7:00 PM</option>
                                    <option value="77">7:15 PM</option>
                                    <option value="78">7:30 PM</option>
                                    <option value="79">7:45 PM</option>
                                    <option value="80">8:00 PM</option>
                                    <option value="81">8:15 PM</option>
                                    <option value="82">8:30 PM</option>
                                    <option value="83">8:45 PM</option>
                                    <option value="84">9:00 PM</option>
                                    <option value="85">9:15 PM</option>
                                    <option value="86">9:30 PM</option>
                                    <option value="87">9:45 PM</option>
                                    <option value="88">10:00 PM</option>
                                    <option value="89">10:15 PM</option>
                                    <option value="90">10:30 PM</option>
                                    <option value="91">10:45 PM</option>
                                    <option value="92">11:00 PM</option>
                                    <option value="93">11:15 PM</option>
                                    <option value="94">11:30 PM</option>
                                    <option value="95">11:45 PM</option>
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