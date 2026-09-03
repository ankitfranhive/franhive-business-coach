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
                        <h4>Add A new Lead</h4>
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
            <form action="<?= base_url('leadController/createNewLead') ?>" method="post">
                <h5>Personal Details</h5>
                <br>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Job Title</label>
                            <div class="col-md-8">
                                <select name="TITLE" id="ENTITY_TITLE" class="form-control" onchange="">
                                    <option value="-1">Select</option>
                                    <option value="1">Dr.</option>
                                    <option value="2">Miss</option>
                                    <option value="3">Mr.</option>
                                    <option value="4">Mrs.</option>
                                    <option value="5">Ms.</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">User Name <span style="color: red;">*</span></label>
                            <div class="col-md-8">
                                <input class="form-control" type="text" name="NAME" placeholder="User Name" required />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Email <span style="color: red;">*</span></label>
                            <div class="col-md-8">
                                <input class="form-control" type="email" name="EMAIL" placeholder="User Email" required />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Mobile <span style="color: red;">*</span></label>
                            <div class="col-md-8">
                                <input class="form-control" type="text" name="PHONE" placeholder="User Mobile" required />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Business type</label>
                            <div class="col-md-8">
                                <select name="BUISNESS" id="ENTITY_BUISNESS" class="form-control" onchange="">
                                    <option value="-1">Select</option>
                                    <?php foreach ($all_business_type as $business_type) : ?>
                                        <option value="<?= $business_type['ID'] ?>"><?= $business_type['NAME'] ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Preferred Mode of Contact</label>
                            <div class="col-md-8">
                                <select name="PREF_MODE_CONTACT" id="ENTITY_PREF_MODE_CONTACT" class="form-control" onchange="">
                                    <option value="-1">Select</option>
                                    <option value="1">Work Phone</option>
                                    <option value="2">Home Phone</option>
                                    <option value="3">Fax</option>
                                    <option value="4">Mobile</option>
                                    <option value="5">Email</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Best Time To Contact</label>
                            <div class="col-md-8">
                                <input class="form-control" type="text" name="BEST_TIME_CONTACT" placeholder="BEST TIME TO CONNECT" />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Work Phone</label>
                            <div class="col-md-8">
                                <input class="form-control" type="number" name="HOME_PHONE" placeholder="WORK PHONE" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-form-label col-md-2">Address line 1</label>
                    <div class="col-md-10">
                        <input class="form-control" type="text" name="ADDRESS_LINE_1" placeholder="Address Line 1" />
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-form-label col-md-2">Address Line 2</label>
                    <div class="col-md-10">
                        <input class="form-control" type="text" name="ADDRESS_LINE_2" placeholder="Address Line 2" />
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">City</label>
                            <div class="col-md-8">
                                <input class="form-control" type="text" name="CITY" placeholder="City" />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Zip</label>
                            <div class="col-md-8">
                                <input class="form-control" type="text" name="ZIP" placeholder="Pin Code" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Country</label>
                            <div class="col-md-8">
                                <input class="form-control" type="text" name="COUNTRY" placeholder="Country" />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">State</label>
                            <div class="col-md-8">
                                <input class="form-control" type="text" name="STATE" placeholder="State" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-form-label col-md-2">Comments </label>
                    <div class="col-md-10">
                        <input class="form-control" type="text" name="COMMENTS" placeholder="COMMENTS" />
                    </div>
                </div>

                <div style="border-bottom: 1px dotted #000;"></div>
                <br>

                <h5>Lead Details</h5>
                <br>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Lead Status</label>
                            <div class="col-md-8">
                                <select name="LEAD_STATUS" id="ENTITY_LEAD_STATUS" class="form-control" onchange="">
                                    <option value="-1">Select</option>
                                    <option value="1">New</option>
                                    <option value="2">Closed</option>
                                    <option value="3">Red Flag</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Lead Owner</label>
                            <div class="col-md-8">
                                <select class="custom-select col-12" name="LEAD_OWNER">
                                    <option selected="">Choose...</option>
                                    <?php foreach ($all_users as $user) : ?>
                                        <option value="<?= $user['USER_ID'] ?>"><?= $user['NAME'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">

                <div class="form-group row">
    <label class="col-form-label col-md-4">Lead Source</label>
    <div class="col-md-8">
        <select name="LEAD_SOURCE" id="ENTITY_LEAD_SOURCE" class="form-control">
            <option value="-1">Select</option>
            <option value="1">Advertisement</option>
            <option value="2">Brokers</option>
            <option value="3">Friends</option>
            <option value="4">Import</option>
            <option value="5">Internet</option>
            <option value="6">Referred By</option>
            <option value="7">Search</option>
            <option value="8">Landing Page</option>
            <option value="69676451">Work Shop</option>
            <option value="11831307">Training/ Coaching Session</option>
            <option value="38926522">Social Media</option>
        </select>
    </div>
</div>
                                    </div>
                                    <div class="col-md-6">
<div class="form-group row">
    <label class="col-form-label col-md-4">Lead Source Detail</label>
    <div class="col-md-8">
        <select name="LEAD_SOURCE_DETAIL" id="ENTITY_LEAD_SOURCE_DETAIL" class="form-control">
            <option value="-1">Select Lead Source First</option>
        </select>
    </div>
</div>
</div>
</div>


                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Current Net Worth</label>
                            <div class="col-md-8">
                                <input class="form-control" type="text" name="CURRENT_NET_WORTH" placeholder="CURRENT NET WORTH" />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Cash Available for Investment</label>
                            <div class="col-md-8">
                                <input class="form-control" type="text" name="CASH_AVAILABLE_INVESTMENT" placeholder="CASH AVAILABLE INVESTMENT" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Investment Timeframe</label>
                            <div class="col-md-8">
                                <input class="form-control" type="text" name="INVESTMENT_TIMEFRAME" placeholder="INVESTMENT TIMEFRAME" />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Background</label>
                            <div class="col-md-8">
                                <input class="form-control" type="text" name="BACKGROUND" placeholder="BACKGROUND" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Source Of Investment</label>
                            <div class="col-md-8">
                                <input class="form-control" type="text" name="SOURCE_OF_INVESTMENT" placeholder="SOURCE OF INVESTMENT" />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Next Call Date</label>
                            <div class="col-md-8">
                                <input class="form-control" type="date" name="NEXT_CALL_DATE" placeholder="NEXT CALL DATE" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-6 offset-md-2">
                        <button type="submit" class="btn btn-warning">Add Lead</button>
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
        $('#choose_country').change(function() {
            var country_id = $(this).val();
            $.ajax({
                url: "<?php echo base_url('usercontroller/get_states_by_country_id'); ?>",
                type: "POST",
                data: {
                    country_id: country_id
                },
                dataType: "json",
                success: function(data) {
                    var state_select = $('select[name="STATE"]');
                    state_select.empty();
                    state_select.append('<option selected="">Choose...</option>');
                    $.each(data, function(key, value) {
                        state_select.append('<option value="' + value.STATE_ID + '">' + value.STATE_NAME + '</option>');
                    });
                }
            });
        });
    });
</script>

<script>
    // Map Lead Source (parent) → Lead Source Detail (child)
    // Keys = LEAD_SOURCE values, Values = array of detail options (value + label)
    var leadSourceDetailsMap = {
        // 1 - Advertisement
        '1': [
            { value: '1', text: 'Magazine' },
            { value: '2', text: 'Newspaper' },
            { value: '4', text: 'Trade Shows' },
            { value: '3', text: 'Other (Advertisement)' }
        ],

        // 2 - Brokers
        '2': [
            { value: '3', text: 'Other (Broker)' }
        ],

        // 3 - Friends
        '3': [
            { value: '5', text: 'Friends / Personal Reference' },
            { value: '3', text: 'Other (Reference)' }
        ],

        // 4 - Import
        '4': [
            { value: '3', text: 'Other (Import)' }
        ],

        // 5 - Internet
        // Example you gave: website, social media, ads
        '5': [
            { value: '10', text: 'Website / Landing Page' },  // existing "Landing Page"
            { value: '6',  text: 'Search (Google)' },
            { value: '7',  text: 'Search (Bing)' },
            { value: '9',  text: 'Search (Yahoo)' },
            { value: '38283371', text: 'Social Media (Facebook/Instagram/etc.)' },
            { value: '3',  text: 'Other (Internet)' }
        ],

        // 6 - Referred By
        '6': [
            { value: '5', text: 'Friends / Client Referral' },
            { value: '3', text: 'Other (Referral)' }
        ],

        // 7 - Search
        '7': [
            { value: '6', text: 'Google' },
            { value: '7', text: 'Bing' },
            { value: '9', text: 'Yahoo' },
            { value: '8', text: 'Other (Search Engine)' }
        ],

        // 8 - Landing Page
        '8': [
            { value: '10', text: 'Landing Page' },
            { value: '3',  text: 'Other (Landing Page)' }
        ],

        // 69676451 - Work Shop
        '69676451': [
            { value: '38768978', text: 'LEAD YOUR LEGACY (May 2023)' },
            { value: '3',       text: 'Other (Workshop)' }
        ],

        // 11831307 - Training / Coaching Session
        '11831307': [
            { value: '30150936', text: 'Change your Life and Business Training' },
            { value: '3',        text: 'Other (Training / Coaching)' }
        ],

        // 38926522 - Social Media
        '38926522': [
            { value: '38283371', text: 'Facebook' },
            { value: '8998608',  text: 'Instagram' },
            { value: '59011397', text: 'LinkedIn' },
            { value: '3',        text: 'Other (Social Media)' }
        ]
    };

    function populateLeadSourceDetail(sourceId) {
        var $detailSelect = $('#ENTITY_LEAD_SOURCE_DETAIL');
        $detailSelect.empty();
        $detailSelect.append('<option value="-1">Select</option>');

        if (leadSourceDetailsMap[sourceId]) {
            leadSourceDetailsMap[sourceId].forEach(function (opt) {
                $detailSelect.append(
                    '<option value="' + opt.value + '">' + opt.text + '</option>'
                );
            });
        } else {
            // Fallback: no mapping → show a generic "Other"
            $detailSelect.append('<option value="3">Other</option>');
        }
    }

    $(document).ready(function () {
        // Existing country/state AJAX can stay as is...

        $('#ENTITY_LEAD_SOURCE').on('change', function () {
            var sourceId = $(this).val();
            populateLeadSourceDetail(sourceId);
        });
    });
</script>


</html>
