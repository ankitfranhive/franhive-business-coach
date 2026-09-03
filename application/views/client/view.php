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
                        <h4>View Client</h4>
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

            </div>
            <h5>Client Details</h5>
            <br>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-form-label col-md-4">Client ID <span style="color: red;">*</span></label>
                        <div class="col-md-8">
                            <?= $client_data["STORE_ID"] ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-form-label col-md-4">Client Name <span style="color: red;">*</span></label>
                        <div class="col-md-8">
                            <?= $client_data["STORE_NAME"] ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-form-label col-md-4">Client Type <span style="color: red;">*</span></label>
                        <div class="col-md-8">
                            <?php foreach ($client_types as $client) : ?>
                                <?= $client_data["IS_FRANCHISEE"] == $client['ID'] ? $client['NAME'] : '' ?>
                            <?php endforeach ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-form-label col-md-4">Division <span style="color: red;">*</span></label>
                        <div class="col-md-8">
                            <?php
                            $divisions = explode(',', $client_data['DIVISION']); // Split the division string into an array
                            $divisionNames = [];

                            foreach ($divisions as $division) {
                                switch ($division) {
                                    case '13814739':
                                        $divisionNames[] = 'North';
                                        break;
                                    case '27083643':
                                        $divisionNames[] = 'South';
                                        break;
                                    case '28397879':
                                        $divisionNames[] = 'East';
                                        break;
                                    case '62312528':
                                        $divisionNames[] = 'West';
                                        break;
                                    default:
                                        $divisionNames[] = 'None'; // If division not found
                                        break;
                                }
                            }

                            // Join the division names with a comma and print them
                            echo implode(', ', $divisionNames);
                            ?>
                        </div>
                    </div>
                </div>
            </div>



            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-form-label col-md-4">Business type<span style="color: red;">*</span></label>
                        <div class="col-md-8">
                            <?php foreach ($all_business_type as $business_type) : ?>
                                <?= $client_data['STORE_TYPE'] == $business_type['ID'] ? $business_type['NAME'] : '' ?>
                            <?php endforeach ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-form-label col-md-4">Lead Owner <span style="color: red;">*</span></label>
                        <div class="col-md-8">
                            <?php foreach ($all_users as $user) : ?>
                                <?= $client_data['LEAD_OWNER'] == $user['USER_ID'] ? $user['NAME'] : '' ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-form-label col-md-4">Ratings <span style="color: red;">*</span></label>
                        <div class="col-md-8">
                            <?php foreach ($ratings as $rate) : ?>
                                <?= $client_data['FORECAST_RATING'] ==  $rate['ID'] ? $rate['RATING'] : '' ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>


            <div class="form-group row">
                <label class="col-form-label col-md-2">Address line 1 <span style="color: red;">*</span></label>
                <div class="col-md-10">
                    <?= $client_data['ADDRESS_LINE_1'] ?>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-form-label col-md-2">Address Line 2 <span style="color: red;">*</span></label>
                <div class="col-md-10">
                    <?= $client_data['ADDRESS_LINE_2'] ?>

                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-form-label col-md-4">City <span style="color: red;">*</span></label>
                        <div class="col-md-8">
                            <?= $client_data['CITY'] ?>

                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-form-label col-md-4">Zip <span style="color: red;">*</span></label>
                        <div class="col-md-8">
                            <?= $client_data['ZIP'] ?>

                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-form-label col-md-4">Country <span style="color: red;">*</span></label>
                        <div class="col-md-8">
                            <?= $client_data['COUNTRY'] ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-form-label col-md-4">State <span style="color: red;">*</span></label>
                        <div class="col-md-8">
                            <?= $client_data['STATE'] ?>


                        </div>

                    </div>
                </div>
            </div>
            <div class="row">

                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-form-label col-md-4">Phone <span style="color: red;">*</span></label>
                        <div class="col-md-8">
                            <?= $client_data['PHONE'] ?>

                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-form-label col-md-4">Email <span style="color: red;">*</span></label>
                        <div class="col-md-8">
                            <?= $client_data['EMAIL'] ?>

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
                        <label class="col-form-label col-md-4">Start Date <span style="color: red;">*</span></label>
                        <div class="col-md-8">
                            <?= $client_data['START_DATE'] ?>

                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-form-label col-md-4">End Date <span style="color: red;">*</span></label>
                        <div class="col-md-8">
                            <?= $client_data['END_DATE'] ?>

                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-form-label col-md-4">Birth Date <span style="color: red;">*</span></label>
                        <div class="col-md-8">
                            <?= $client_data['BIRTH_DATE'] ?>

                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-form-label col-md-4">Courses Assigned <span style="color: red;">*</span></label>
                        <div class="col-md-8">
                            <?php
                            // Convert the comma-separated string of course IDs into an array
                            $selectedCourses = explode(',', $client_data['COURSE_ID']);

                            // Loop through the courses and display the ones that are selected
                            foreach ($courses as $course) {
                                if (in_array($course['COURSE_ID'], $selectedCourses)) {
                                    echo '<p>' . $course['NAME'] . '</p>';
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-form-label col-md-4">Website <span style="color: red;">*</span></label>
                        <div class="col-md-8">
                            <a href=". <?= $client_data['WEBSITE'] ?>." target="_blank"><?= $client_data['WEBSITE'] ?></a>

                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-form-label col-md-4">Linkedin Link <span style="color: red;">*</span></label>
                        <div class="col-md-8">
                            <?= $client_data['LINKEDIN_LINK'] ?>
                        </div>
                    </div>
                </div>
            </div>

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
</script>

</html>