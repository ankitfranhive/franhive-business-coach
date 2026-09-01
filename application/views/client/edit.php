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
                        <h4>Edit Client</h4>
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

                <?php if ($error = $this->session->flashdata('error')): ?>
                    <div class="pd-20">
                        <div class="alert alert-danger">
                            <?= $error; ?>
                            <?php $this->session->unset_userdata('error'); // Clear the error flashdata 
                            ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($success = $this->session->flashdata('success')): ?>
                    <div class="pd-20">
                        <div class="alert alert-success">
                            <?= $success; ?>
                            <?php $this->session->unset_userdata('success'); // Clear the success flashdata 
                            ?>
                        </div>
                    </div>
                <?php endif; ?>
                <!-- <h4 class="text-blue h4">Listing of all Questions</h4> -->
            </div>
            <form action="<?= base_url('ClientController/updateClient/' . $client_data['ENTITY_ID']) ?>" method="post">
                <h5>Client Details</h5>
                <br>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Client ID  <span style="color: red;">*</span></label>
                            <div class="col-md-8">
                                <input class="form-control" type="text" name="STORE_ID" value="<?= $client_data['STORE_ID']  ?>" placeholder="Client ID" required />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Client Name <span style="color: red;">*</span></label>
                            <div class="col-md-8">
                                <input class="form-control" type="text" name="STORE_NAME" value="<?= $client_data['STORE_NAME']  ?>" placeholder="Client Name" required />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Client Type <span style="color: red;">*</span></label>
                            <div class="col-md-8">
                                <select name="CLIENT_TYPE" id="CLIENT_TYPE" class="form-control" onchange="" required>
                                    <option value="-1" <?= $client_data['IS_FRANCHISEE'] == '-1' ? 'selected' : '' ?>>Select</option>
                                    <?php foreach ($client_types as $client) : ?>
                                        <option value="<?= $client['ID'] ?>" <?= $client_data['IS_FRANCHISEE'] == $client['ID'] ? 'selected' : '' ?>><?= $client['NAME'] ?></option>
                                <?php endforeach ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Division </label>
                            <div class="col-md-8">
                                <?php
                                // Convert the saved division IDs into an array
                                $selectedDivisions = explode(',', $client_data['DIVISION']); // Assuming $client_data['DIVISION'] holds the saved values
                                ?>
                                <select id='multi_division_ids' class="custom-select col-12" name="DIVISION[]" multiple >
                                    <option value="-1" <?php echo in_array('-1', $selectedDivisions) ? 'selected' : ''; ?>>Select All</option>
                                    <option value="13814739" <?php echo in_array('13814739', $selectedDivisions) ? 'selected' : ''; ?>>North</option>
                                    <option value="27083643" <?php echo in_array('27083643', $selectedDivisions) ? 'selected' : ''; ?>>South</option>
                                    <option value="28397879" <?php echo in_array('28397879', $selectedDivisions) ? 'selected' : ''; ?>>East</option>
                                    <option value="62312528" <?php echo in_array('62312528', $selectedDivisions) ? 'selected' : ''; ?>>West</option>
                                </select>
                            </div>
                        </div>
                    </div>

                </div>



                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Business type</label>
                            <div class="col-md-8">
                                <select name="STORE_TYPE" id="STORE_TYPE" class="form-control" onchange="" >
                                    <option value="-1">Select</option>
                                    <?php foreach ($all_business_type as $business_type) : ?>
                                        <option value="<?= $business_type['ID'] ?>" <?= $client_data['STORE_TYPE'] == $business_type['ID'] ? 'selected' : '' ?>><?= $business_type['NAME'] ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Lead Owner  <span style="color: red;">*</span> </label>
                            <div class="col-md-8">
                                <select class="custom-select col-12" name="LEAD_OWNER" required>
                                    <option selected="">Choose...</option>
                                    <?php foreach ($all_users as $user) : ?>
                                        <option value="<?= $user['USER_ID'] ?>" <?= $client_data['LEAD_OWNER'] ==  $user['USER_ID']  ? 'selected' : '' ?>><?= $user['NAME'] ?></option>
                                    <?php endforeach; ?>

                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Ratings </label>
                            <div class="col-md-8">
                                <select class="custom-select col-12" name="FORECAST_RATING" >
                                    <option selected="">Choose...</option>
                                    <?php foreach ($ratings as $rate) : ?>
                                        <option value="<?= $rate['ID'] ?>" <?= $client_data['FORECAST_RATING'] ==  $rate['ID']  ? 'selected' : '' ?>><?= $rate['RATING'] ?></option>
                                    <?php endforeach; ?>

                                </select>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="form-group row">
                    <label class="col-form-label col-md-2">Address line 1 </label>
                    <div class="col-md-10">
                        <input class="form-control" type="text" name="ADDRESS_LINE_1" value="<?= $client_data['ADDRESS_LINE_1'] ?>" placeholder="Address Line 1"  />
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-form-label col-md-2">Address Line 2 </label>
                    <div class="col-md-10">
                        <input class="form-control" type="text" name="ADDRESS_LINE_2" value="<?= $client_data['ADDRESS_LINE_2'] ?>" placeholder="Address Line 2"  />
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">City </label>
                            <div class="col-md-8">
                                <input class="form-control" type="text" name="CITY" value="<?= $client_data['CITY'] ?>" placeholder="City"  />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Zip </label>
                            <div class="col-md-8">
                                <input class="form-control" type="text" name="ZIP" value="<?= $client_data['ZIP'] ?>" placeholder="Pin Code"  />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Country </label>
                            <div class="col-md-8">
                                <input class="form-control" type="text" name="COUNTRY" value="<?= $client_data['COUNTRY'] ?>" placeholder="Country"  />

                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">State </label>
                            <div class="col-md-8">
                                <input class="form-control" type="text" name="STATE" value="<?= $client_data['STATE'] ?>" placeholder="State"  />

                            </div>

                        </div>
                    </div>
                </div>
                <div class="row">

                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Phone  <span style="color: red;">*</span></label>
                            <div class="col-md-8">

                                <input class="form-control" type="text" name="PHONE" value="<?= $client_data['PHONE'] ?>" placeholder="Phone"  />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Email  <span style="color: red;">*</span></label>
                            <div class="col-md-8">

                                <input class="form-control" type="text" name="EMAIL" value="<?= $client_data['EMAIL'] ?>" placeholder="Email" required />
                            </div>
                        </div>
                    </div>
                </div>

                <div style="border-bottom: 1px dotted #000;">
                </div>
                <br>

                <h5>Other Information</h5>
                <br>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Start Date </label>
                            <div class="col-md-8">
                                <?php
                                // Extract the date part from the datetime string
                                $startDate = date('Y-m-d', strtotime($client_data['START_DATE']));
                                ?>
                                <input class="form-control" type="date" name="START_DATE" value="<?= $startDate ?>" placeholder="Start date"  />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">End Date </label>
                            <div class="col-md-8">
                                <?php
                                // Extract the date part from the datetime string
                                $endDate = date('Y-m-d', strtotime($client_data['END_DATE']));
                                ?>
                                <input class="form-control" type="date" name="END_DATE" value="<?= $endDate ?>" placeholder="End date"  />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Birth Date </label>
                            <div class="col-md-8">

                                <input class="form-control" type="date" name="BIRTH_DATE" value="<?= date('Y-m-d', strtotime($client_data['BIRTH_DATE'])) ?>" placeholder="Birth date"  />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Courses Assigned </label>
                            <div class="col-md-8">
                                <?php $selectedCourses = explode(',', $client_data['COURSE_ID']); ?>
                                <select id='multi_courses_ids' class="custom-select col-12" name="COURSE_ID[]" multiple >
                                    <option value="-1">Choose...</option>
                                    <?php foreach ($courses as $course) : ?>
                                        <option value="<?= $course['COURSE_ID'] ?>"
                                            <?= in_array($course['COURSE_ID'], $selectedCourses) ? 'selected' : '' ?>>
                                            <?= $course['NAME'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Website </label>
                            <div class="col-md-8">
                                <input class="form-control" type="text" name="WEBSITE" value="<?= $client_data['WEBSITE']  ?>" placeholder="Website"  />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Linkedin Link </label>
                            <div class="col-md-8">
                                <input class="form-control" type="text" name="LINKEDIN_LINK" value="<?= $client_data['LINKEDIN_LINK']  ?>" placeholder="Website"  />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-6 offset-md-2">
                        <button type="submit" class="btn btn-warning">Update Client</button>
                    </div>
                </div>
            </form>
        </div>
        <!-- Default Basic Forms End -->
    </div>
</div>
</body>
<?php $this->load->view('includes/footer'); ?>


<script>
    $(document).ready(function() {
        $('#multi_permission_ids').select2();
    });

    $(document).ready(function() {
        // Initialize Select2
        $('#multi_division_ids').select2();

        $('#multi_division_ids').on('change', function() {
            var selectedValues = $(this).val();
            if (selectedValues.includes('-1')) {
                // If 'Select All' is selected, select all options except 'Select All'
                $('#multi_division_ids > option').each(function() {
                    if (this.value != '-1') {
                        $(this).prop('selected', true);
                    }
                });
                // Deselect 'Select All'
                $('#multi_division_ids > option[value="-1"]').prop('selected', false);
            } else {
                // If any option is deselected, ensure 'Select All' is deselected
                if (selectedValues.length < $('#multi_division_ids > option').length - 1) {
                    $('#multi_division_ids > option[value="-1"]').prop('selected', false);
                }
            }
            $('#multi_division_ids').trigger('change.select2');
        });
    });

    $(document).ready(function() {
        $('#multi_courses_ids').select2();
    });

    $(document).ready(function() {
        // Initialize Select2
        $('#multi_courses_ids').select2();

        $('#multi_courses_ids').on('change', function() {
            var selectedValues = $(this).val();
            if (selectedValues.includes('-1')) {
                // If 'Select All' is selected, select all options except 'Select All'
                $('#multi_courses_ids > option').each(function() {
                    if (this.value != '-1') {
                        $(this).prop('selected', true);
                    }
                });
                // Deselect 'Select All'
                $('#multi_courses_ids > option[value="-1"]').prop('selected', false);
            } else {
                // If any option is deselected, ensure 'Select All' is deselected
                if (selectedValues.length < $('#multi_courses_ids > option').length - 1) {
                    $('#multi_courses_ids > option[value="-1"]').prop('selected', false);
                }
            }
            $('#multi_courses_ids').trigger('change.select2');
        });
    });
</script>

</html>