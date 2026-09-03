<!DOCTYPE html>
<html>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<style>
    .select2-selection__choice {
        padding-left: 25px !important;
    }
</style>


<?php $this->load->view('includes/header'); ?>

<div class="mobile-menu-overlay"></div>
<div class="main-container">
    <div class="pd-ltr-20 xs-pd-20-10">
        <div class="page-header">
            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <div class="title">
                        <h4>View User</h4>
                    </div>
                </div>
                <div class="col-md-6 col-sm-12 text-right">
                    <a class="btn btn-warning" href="<?= base_url('/users'); ?>" role="button">
                        Back To User List
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

            <input type="hidden" name="id" value="<?= $user_data['USER_ID'] ?>">

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-form-label col-md-4">Job Title <span style="color: red;">*</span></label>
                        <div class="col-md-8">
                            <p class="form-control-static">
                                <?php
                                switch ($user_data['JOB_TITLE']):
                                    case '50805861':
                                        echo 'Mr.';
                                        break;
                                    case '19862902':
                                        echo 'Miss';
                                        break;
                                    case '39140005':
                                        echo 'Mrs.';
                                        break;
                                    default:
                                        echo ''; // Default value if none match
                                        break;
                                endswitch;
                                ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-form-label col-md-4">User Name <span style="color: red;">*</span></label>
                        <div class="col-md-8">
                            <p class="form-control-static">
                                <?= $user_data['NAME']  ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-form-label col-md-4">Email <span style="color: red;">*</span></label>
                        <div class="col-md-8">
                            <p class="form-control-static">
                                <?= $user_data['EMAIL']  ?>
                            </p>

                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-form-label col-md-4">Mobile <span style="color: red;">*</span></label>
                        <div class="col-md-8">
                            <p class="form-control-static">
                                <?= $user_data['MOBILE']  ?>
                            </p>

                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-form-label col-md-4">User Type <span style="color: red;">*</span></label>
                        <div class="col-md-8">
                            <p class="form-control-static">
                                <?php
                                foreach ($roles as $role) {
                                    if ($user_data['USERS_TYPE_ID'] == $role['ROLE_ID']) {
                                        echo $role['ROLE_NAME'];
                                    }
                                } ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-form-label col-md-4">Permissions <span style="color: red;">*</span></label>
                        <div class="col-md-8">
                            <p class="form-control-static">
                            <ul>
                                <?php
                                $selected_permissions = explode(',', $user_data['PERMISSION_ID']);
                                foreach ($permissions as $permission) {
                                    if (in_array($permission['PERMISSION_ID'], $selected_permissions)) {
                                        echo '<li>' . htmlspecialchars($permission['PERMISSION_NAME']) . '</li>';
                                    }
                                } ?>
                            </ul>
                            </p>
                        </div>

                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-form-label col-md-4">Division <span style="color: red;">*</span></label>
                        <div class="col-md-8">
                            <p class="form-control-static">
                                <?php
                                switch ($user_data['DIVISION']):
                                    case '13814739':
                                        echo 'North';
                                        break;
                                    case '27083643':
                                        echo 'South';
                                        break;
                                    case '28397879':
                                        echo 'East';
                                        break;
                                    case '62312528':
                                        echo 'West';
                                        break;
                                    default:
                                        echo ''; // Default value if none match
                                        break;
                                endswitch;
                                ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-form-label col-md-4">Client <span style="color: red;">*</span></label>
                        <div class="col-md-8">
                            <p class="form-control-static">
                                <?php
                                switch ($user_data['CLIENT_TYPE_ID']):
                                    case 1:
                                        echo 'EYE';
                                        break;
                                    case 2:
                                        echo 'franhive';
                                        break;
                                    case 3:
                                        echo 'CarWell';
                                        break;

                                    default:
                                        echo $user_data['CLIENT_TYPE_ID']; // Default value if none match
                                        break;
                                endswitch;
                                ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-form-label col-md-2">Address line 1 <span style="color: red;">*</span></label>
                <div class="col-md-10">
                    <p class="form-control-static">
                        <?= $user_data['ADDRESS_LINE_1']  ?>
                    </p>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-form-label col-md-2">Address Line 2 <span style="color: red;">*</span></label>
                <div class="col-md-10">
                    <p class="form-control-static">
                        <?= $user_data['ADDRESS_LINE_2']  ?>
                    </p>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-form-label col-md-4">City <span style="color: red;">*</span></label>
                        <div class="col-md-8">
                            <p class="form-control-static">
                                <?= $user_data['CITY']  ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-form-label col-md-4">Zip <span style="color: red;">*</span></label>
                        <div class="col-md-8">
                            <p class="form-control-static">
                                <?= $user_data['ZIP']  ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-form-label col-md-4">Country <span style="color: red;">*</span></label>
                        <div class="col-md-8">
                            <?= $user_data['COUNTRY']  ?>
                            <!-- <select id="country" name="country"></select> -->
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-form-label col-md-4">State <span style="color: red;">*</span></label>
                        <div class="col-md-8"><?= $user_data['STATE']  ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-form-label col-md-4">Note <span style="color: red;">*</span></label>
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-12">
                                    <?= $user_data['NOTE']  ?>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-form-label col-md-4">Profile Picture <span style="color: red;">*</span></label>
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-12">
                                    <a href="<?= $user_data['PROFILE_PICTURE']  ?>" target="_blank">View Uploded File</a>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-form-label col-md-4">Enrollment agreement<span style="color: red;">*</span></label>
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-12">
                                    <a href="<?= $user_data['ENROLLMENT_AGREEMENT']  ?>" target="_blank">View Uploded File</a>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-form-label col-md-4">Payment agreement<span style="color: red;">*</span></label>
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-12">
                                    <a href="<?= $user_data['PAYMENT_AGREEMENT']  ?>" target="_blank">View Uploded File</a>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-form-label col-md-4">User Password<span style="color: red;">*</span></label>
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-12">
                                <?= $user_data['PASSWORD']  ?>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <hr>
            <div class="clearfix mb-2">
                <h5 class="text-blue h5 mb-0">Enrolled Courses</h5>
                <small class="text-muted">Courses assigned to this user</small>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th style="width:80px;">ID</th>
                            <th style="width:120px;">Thumbnail</th>
                            <th>Course Name</th>
                            <th style="width:180px;">Enrolled On</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($user_courses)): ?>
                            <?php foreach ($user_courses as $course): ?>
                                <tr>
                                    <td><?= (int)($course['COURSE_ID'] ?? 0); ?></td>
                                    <td>
                                        <?php if (!empty($course['THUMNAIL_IMAGE'])): ?>
                                            <img
                                                src="<?= html_escape($course['THUMNAIL_IMAGE']); ?>"
                                                alt="<?= html_escape($course['NAME'] ?? 'Course'); ?>"
                                                style="width:90px;height:55px;object-fit:cover;border-radius:4px;"
                                            >
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= html_escape($course['NAME'] ?? '-'); ?></td>
                                    <td><?= html_escape(!empty($course['ENROLLED_ON']) ? $course['ENROLLED_ON'] : '-'); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center text-muted">No courses enrolled for this user.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>


        </div>
        <!-- Default Basic Forms End -->
    </div>
</div>
</body>
<?php $this->load->view('includes/footer'); ?>
<!-- Load CKEditor -->

<script>
    // Array of countries
    const countries = [
        "Afghanistan", "Albania", "Algeria", "Andorra", "Angola", "Antigua and Barbuda", "Argentina", "Armenia", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bhutan", "Bolivia", "Bosnia and Herzegovina", "Botswana", "Brazil", "Brunei", "Bulgaria", "Burkina Faso", "Burundi", "Côte d'Ivoire", "Cabo Verde", "Cambodia", "Cameroon", "Canada", "Central African Republic", "Chad", "Chile", "China", "Colombia", "Comoros", "Congo (Congo-Brazzaville)", "Costa Rica", "Croatia", "Cuba", "Cyprus", "Czechia (Czech Republic)", "Democratic Republic of the Congo", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Eswatini (fmr. 'Swaziland')", "Ethiopia", "Fiji", "Finland", "France", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Greece", "Grenada", "Guatemala", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Holy See", "Honduras", "Hungary", "Iceland", "India", "Indonesia", "Iran", "Iraq", "Ireland", "Israel", "Italy", "Jamaica", "Japan", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Kuwait", "Kyrgyzstan", "Laos", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libya", "Liechtenstein", "Lithuania", "Luxembourg", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Mauritania", "Mauritius", "Mexico", "Micronesia", "Moldova", "Monaco", "Mongolia", "Montenegro", "Morocco", "Mozambique", "Myanmar (formerly Burma)", "Namibia", "Nauru", "Nepal", "Netherlands", "New Zealand", "Nicaragua", "Niger", "Nigeria", "North Korea", "North Macedonia", "Norway", "Oman", "Pakistan", "Palau", "Palestine State", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Poland", "Portugal", "Qatar", "Romania", "Russia", "Rwanda", "Saint Kitts and Nevis", "Saint Lucia", "Saint Vincent and the Grenadines", "Samoa", "San Marino", "Sao Tome and Principe", "Saudi Arabia", "Senegal", "Serbia", "Seychelles", "Sierra Leone", "Singapore", "Slovakia", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "South Korea", "South Sudan", "Spain", "Sri Lanka", "Sudan", "Suriname", "Sweden", "Switzerland", "Syria", "Tajikistan", "Tanzania", "Thailand", "Timor-Leste", "Togo", "Tonga", "Trinidad and Tobago", "Tunisia", "Turkey", "Turkmenistan", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States of America", "Uruguay", "Uzbekistan", "Vanuatu", "Venezuela", "Vietnam", "Yemen", "Zambia", "Zimbabwe"
    ];

    // Get the select element
    const select = document.getElementById('country');

    // Populate the select box with options
    countries.forEach(country => {
        const option = document.createElement('option');
        option.textContent = country;
        select.appendChild(option);
    });

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