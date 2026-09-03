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
                        <h4>Enrollment and Payment Agreement</h4>
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
            <form name="contact-form-container" id="contact-form-container" action="https://eyd.franhive.com/fh/webcontent/agreementConfirmation.jsp" method="post" accept-charset="UTF-8" class="form-container" autocomplete="off" onsubmit="javascript:submitForm()">
                <input type="hidden" name="p" id="p" value="1">
                <input type="hidden" name="p1" id="p1" value="9378">
                <input type="hidden" name="p2" id="p2" value="1">
                <input type="hidden" name="webFormName" id="webFormName" value="Enrollment and Payment Agreement">
                <input type="hidden" name="approvalName" id="approvalName" value="">


                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" name="name" id="name" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="address">Postal Address</label>
                    <input type="text" name="address" id="address" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="city">City/Suburb</label>
                    <input type="text" name="city" id="city" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="postcode">Postcode</label>
                    <input type="text" name="postcode" id="postcode" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="country">Country</label>
                    <input type="text" name="country" id="country" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="mobile">Contact Number (mobile)</label>
                    <input type="text" name="mobile" id="mobile" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="workphone">Contact Number (work)</label>
                    <input type="text" name="workphone" id="workphone" class="form-control">
                </div>

                <div class="form-group">
                    <label for="birthdate">D.O.Birth</label>
                    <input type="text" id="birthdate" name="birthdate" class="form-control  date-icon" required>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="emergencycontactname">Emergency Contact Name</label>
                    <input type="text" name="emergencycontactname" id="emergencycontactname" class="form-control">
                </div>

                <div class="form-group">
                    <label for="emergencycontactNumber">Emergency Contact Number</label>
                    <input type="text" name="emergencycontactNumber" id="emergencycontactNumber" class="form-control">
                </div>

                <div class="form-group">
                    <label>TRAINING</label>
                    <div class="form-group">
                        <label class="checkbox">
                            <input name="training1" id="training1" type="checkbox" class="companyField">
                            NLP Practitioner Certification Training
                        </label>
                    </div>

                    <div class="form-group">
                        <label for="datetrainingMonth1">Training Month</label>
                        <select name="datetrainingMonth1" id="datetrainingMonth1" class="companyField form-control">
                            <option value>Select</option>
                            <option value="January">January</option>
                            <option value="February">February</option>
                            <option value="March">March</option>
                            <option value="April">April</option>
                            <option value="May">May</option>
                            <option value="June">June</option>
                            <option value="July">July</option>
                            <option value="August">August</option>
                            <option value="September">September</option>
                            <option value="October">October</option>
                            <option value="November">November</option>
                            <option value="December">December</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="datetrainingYear1">Training Year</label>
                        <select name="datetrainingYear1" id="datetrainingYear1" class="companyField form-control">
                            <option value>Select</option>
                            <option value="2023">2023</option>
                            <option value="2024">2024</option>
                            <option value="2025">2025</option>
                            <option value="2026">2026</option>
                            <option value="2027">2027</option>
                            <option value="2028">2028</option>
                            <option value="2029">2029</option>
                            <option value="2030">2030</option>
                            <option value="2031">2031</option>
                            <option value="2032">2032</option>
                        </select>
                    </div>
                </div>

                <!-- Repeat the above form-group for other trainings as necessary -->

                <div class="form-group">
                    <label for="location">Location</label>
                    <select name="location" id="location" class="companyField form-control" required>
                        <option value>Select</option>
                        <option value="Live">Live</option>
                        <option value="Online">Online</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="fee">Total (inc GST) Fee</label>
                    <input type="text" name="fee" id="fee" class="companyField form-control" required>
                </div>

                <div class="form-group">
                    <label for="notes">Notes</label>
                    <input type="text" name="notes" id="notes" class="companyField form-control">
                </div>

                <div class="form-group">
                    <label for="totalfee">Total</label>
                    <input type="text" name="totalfee" id="totalfee" class="companyField form-control">
                </div>

                <div class="form-group">
                    <label for="depositAmount1">Deposit amount</label>
                    <input type="text" name="depositAmount1" id="depositAmount1" class="companyField form-control">
                </div>

                <div class="form-group">
                    <label for="depositForTraining1">For Training</label>
                    <input type="text" name="depositForTraining1" id="depositForTraining1" class="companyField form-control">
                </div>

                <div class="form-group">
                    <label for="depositPaymentoption1">Deposit Paid via</label>
                    <select name="depositPaymentoption1" id="depositPaymentoption1" class="companyField form-control">
                        <option value="creditCard">Credit Card</option>
                        <option value="fundTransfer">Bank Transfer</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="remainingAmount1">Remaining Amount</label>
                    <input type="text" name="remainingAmount1" id="remainingAmount1" class="companyField form-control">
                </div>

                <div class="form-group">
                    <label for="remainingTraining1">For Training</label>
                    <input type="text" name="remainingTraining1" id="remainingTraining1" class="companyField form-control">
                </div>

                <div class="form-group">
                    <label for="remainingPaymentoption1">Remaining Paid via</label>
                    <select name="remainingPaymentoption1" id="remainingPaymentoption1" class="companyField form-control">
                        <option value="creditCard">Credit Card</option>
                        <option value="fundTransfer">Bank Transfer</option>
                    </select>
                </div>

                <div class="form-group row">
                    <div class="col-md-6 ">
                        <button type="submit" class="btn btn-warning" id="submitButton" name="submitButton">Submit</button>
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