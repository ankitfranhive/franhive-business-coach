<!DOCTYPE html>
<html>
<header>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <style>
        .select2-selection__choice {
            padding-left: 25px !important;
        }
    </style>
</header>

<?php $this->load->view('includes/header'); ?>

<div class="mobile-menu-overlay"></div>
<div class="main-container">
    <div class="pd-ltr-20 xs-pd-20-10">
        <div class="page-header">
            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <div class="title">
                        <h4>Add A new User</h4>
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
            <form action="<?= base_url('userController/addUser') ?>" method="post" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Job Title <span style="color: red;">*</span></label>
                            <div class="col-md-8">
                                <select class="custom-select col-12" name="JOB_TITLE" required>
                                    <option value="-1">Select</option>
                                    <option value="50805861">Mr.</option>
                                    <option value="19862902">Miss</option>
                                    <option value="39140005">Mrs.</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">User Name <span style="color: red;">*</span></label>
                            <div class="col-md-8">
                                <input class="form-control" type="text" name="NAME" placeholder="User Name"  required/>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Email <span style="color: red;">*</span></label>
                            <div class="col-md-8">
                                <input class="form-control" type="text" name="EMAIL" placeholder="User Email" required />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Mobile <span style="color: red;">*</span></label>
                            <div class="col-md-8">
                                <input class="form-control" type="text" name="MOBILE" placeholder="User Mobile" required />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">User Type <span style="color: red;">*</span></label>
                            <div class="col-md-8">
                                <select class="custom-select col-12" name="USERS_TYPE_ID" required>
                                    <option value="-1">Select</option>
                                    <?php
                                    foreach ($roles as $role) { ?>
                                        <option value="<?= $role['ROLE_ID'] ?>"><?= $role['ROLE_NAME'] ?></option>
                                    <?php
                                    } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Permissions </label>
                            <div class="col-md-8">
                                <select id='multi_permission_ids' class="custom-select col-12" name="PERMISSION_ID[]" multiple>
                                    <?php
                                    foreach ($permissions as $permission) { ?>
                                        <option value="<?= $permission['PERMISSION_ID'] ?>"><?= $permission['PERMISSION_NAME'] ?></option>
                                    <?php
                                    } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Division </label>
                            <div class="col-md-8">
                                <select id='multi_division_ids' class="custom-select col-12" name="DIVISION" multiple>
                                    <option value="-1">Select All</option>
                                    <option value="13814739">North</option>
                                    <option value="27083643">South</option>
                                    <option value="28397879">East</option>
                                    <option value="62312528">West</option>

                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Client </label>
                            <div class="col-md-8">
                                <select class="custom-select col-12" name="CLIENT">
                                    <option selected="">Choose...</option>
                                    <option value="1">EYD</option>
                                    <option value="2">Franhive</option>
                                    <option value="3">CareWell</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div> -->

                <div class="form-group row">
                    <label class="col-form-label col-md-2">Address line 1 </label>
                    <div class="col-md-10">
                        <input class="form-control" type="text" name="ADDRESS_LINE_1" placeholder="Address Line 1" />
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-form-label col-md-2">Address Line 2 </label>
                    <div class="col-md-10">
                        <input class="form-control" type="text" name="ADDRESS_LINE_2" placeholder="Address Line 2" />
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">City </label>
                            <div class="col-md-8">
                                <input class="form-control" type="text" name="CITY" placeholder="City" />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Zip </label>
                            <div class="col-md-8">
                                <input class="form-control" type="text" name="ZIP" placeholder="Pin Code" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Country </label>
                            <div class="col-md-8">
                            <input class="form-control" type="text" name="COUNTRY" placeholder="Country" />

                                <!-- <select id="country" name="country"></select> -->
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">State </label>
                            <div class="col-md-8">
                                <input class="form-control" type="text" name="STATE" placeholder="State" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Note </label>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-12">
                                        <textarea class="form-control" name="NOTE" rows="5" placeholder="Note"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Profile Picture </label>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-12">
                                        <input class="form-control" name="PROFILE_PICTURE" type="file" placeholder="Name" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Enrollment Agreement</label>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-12">
                                        <input class="form-control" name="ENROLLMENT_AGREEMENT" type="file" placeholder="Name" />
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Payment Agreement</label>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-12">
                                        <input class="form-control" name="PAYMENT_AGREEMENT" type="file" placeholder="Name" />
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="form-group row">
                    <div class="col-md-6 offset-md-2">
                        <button type="submit" value="Upload" class="btn btn-warning">Add User</button>
                    </div>
                </div>
            </form>
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