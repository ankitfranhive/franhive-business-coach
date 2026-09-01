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
                        <h4>Edit Lead Details</h4>
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
            <form action="<?= base_url('leadController/updateLead') ?>" method="post">
                <h5>Personal Details</h5>
                <br>
                <input type="hidden" name="id" value="<?= $lead['ENTITY_ID'] ?>">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Job Title <span style="color: red;">*</span> </label>
                            <div class="col-md-8">
                                <select name="TITLE" id="ENTITY_TITLE" class="form-control" onchange="">
                                    <option value="-1" <?= ($lead['TITLE'] == -1) ? 'selected' : '' ?>>Select</option>
                                    <option value="1" <?= ($lead['TITLE'] == 1) ? 'selected' : '' ?>>Dr.</option>
                                    <option value="2" <?= ($lead['TITLE'] == 2) ? 'selected' : '' ?>>Miss</option>
                                    <option value="3" <?= ($lead['TITLE'] == 3) ? 'selected' : '' ?>>Mr.</option>
                                    <option value="4" <?= ($lead['TITLE'] == 4) ? 'selected' : '' ?>>Mrs.</option>
                                    <option value="5" <?= ($lead['TITLE'] == 5) ? 'selected' : '' ?>>Ms.</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">User Name <span style="color: red;">*</span></label>
                            <div class="col-md-8">
                                <!-- User Name -->
                                <input class="form-control" type="text" name="NAME" placeholder="User Name" value="<?= $lead['NAME'] ?>" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Email <span style="color: red;">*</span></label>
                            <div class="col-md-8">
                                <input class="form-control" type="text" name="EMAIL" placeholder="User Email" value="<?= $lead['EMAIL'] ?>" />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Mobile <span style="color: red;">*</span></label>
                            <div class="col-md-8">
                                <!-- Mobile -->
                                <input class="form-control" type="text" name="PHONE" placeholder="User Mobile" value="<?= $lead['PHONE'] ?>" />

                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Business type</label>
                            <div class="col-md-8">
                                <!-- Business Type -->
                                <select name="BUISNESS" id="ENTITY_BUISNESS" class="form-control">
                                    <option value="-1">Select</option>

                                    <?php foreach ($all_business_type as $business_type) : ?>
                                        <option <?= ($lead['BUISNESS'] == $business_type['ID']) ? 'selected' : '' ?> value="<?= $business_type['ID'] ?>"><?= $business_type['NAME'] ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Preferred Mode of Contact </label>
                            <div class="col-md-8">
                                <!-- Preferred Mode of Contact -->
                                <select name="PREF_MODE_CONTACT" id="ENTITY_PREF_MODE_CONTACT" class="form-control">
                                    <option value="-1">Select</option>
                                    <option value="1" <?= ($lead['PREF_MODE_CONTACT'] == 1) ? 'selected' : '' ?>>Work Phone</option>
                                    <option value="2" <?= ($lead['PREF_MODE_CONTACT'] == 2) ? 'selected' : '' ?>>Home Phone</option>
                                    <option value="3" <?= ($lead['PREF_MODE_CONTACT'] == 3) ? 'selected' : '' ?>>Fax</option>
                                    <option value="4" <?= ($lead['PREF_MODE_CONTACT'] == 4) ? 'selected' : '' ?>>Mobile</option>
                                    <option value="5" <?= ($lead['PREF_MODE_CONTACT'] == 5) ? 'selected' : '' ?>>Email</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Best Time To Contact </label>
                            <div class="col-md-8">
                                <!-- Best Time To Contact -->
                                <input class="form-control" type="text" name="BEST_TIME_CONTACT" placeholder="BEST TIME TO CONNECT" value="<?= $lead['BEST_TIME_CONTACT'] ?>" />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Work Phone </label>
                            <div class="col-md-8">
                                <!-- Work Phone -->
                                <input class="form-control" type="number" name="HOME_PHONE" placeholder="WORK PHONE" value="<?= $lead['HOME_PHONE'] ?>" />

                            </div>
                        </div>
                    </div>
                </div>


                <div class="form-group row">
                    <label class="col-form-label col-md-2">Address line 1 </label>
                    <div class="col-md-10">
                        <!-- Address Line 1 -->
                        <input class="form-control" type="text" name="ADDRESS_LINE_1" placeholder="Address Line 1" value="<?= $lead['ADDRESS_LINE_1'] ?>" />
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-form-label col-md-2">Address Line 2 </label>
                    <div class="col-md-10">
                        <input class="form-control" type="text" name="ADDRESS_LINE_2" placeholder="Address Line 2" value="<?= $lead['ADDRESS_LINE_2'] ?>" />
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">City </label>
                            <div class="col-md-8">
                                <!-- City -->
                                <input class="form-control" type="text" name="CITY" placeholder="City" value="<?= $lead['CITY'] ?>" />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Zip </label>
                            <div class="col-md-8">
                                <!-- Zip -->
                                <input class="form-control" type="text" name="ZIP" placeholder="Pin Code" value="<?= $lead['ZIP'] ?>" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Country </label>
                            <div class="col-md-8">
                                <input class="form-control" type="text" name="COUNTRY" placeholder="Country" value="<?= $lead['COUNTRY'] ?>" />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">State </label>
                            <div class="col-md-8">
                                <input class="form-control" type="text" name="STATE" placeholder="State" value="<?= $lead['STATE'] ?>" />
                            </div>

                        </div>
                    </div>
                </div>


                <div class="form-group row">
                    <label class="col-form-label col-md-2">Comments </label>
                    <div class="col-md-10">
                        <!-- Comments -->
                        <input class="form-control" type="text" name="COMMENTS" placeholder="COMMENTS" value="<?= $lead['COMMENTS'] ?>" />

                    </div>
                </div>


                <div style="border-bottom: 1px dotted #000;">
                </div>
                <br>

                <h5>Lead Details</h5>
                <br>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Lead Status </label>
                            <div class="col-md-8">
                                <!-- Lead Status -->
                                <select name="LEAD_STATUS" id="ENTITY_LEAD_STATUS" class="form-control">
                                    <option value="-1">Select</option>
                                    <option value="1" <?= ($lead['LEAD_STATUS'] == 1) ? 'selected' : '' ?>>New</option>
                                    <option value="2" <?= ($lead['LEAD_STATUS'] == 2) ? 'selected' : '' ?>>Closed</option>
                                    <option value="3" <?= ($lead['LEAD_STATUS'] == 3) ? 'selected' : '' ?>>Red Flag</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Lead Owner </label>
                            <div class="col-md-8">
                                <!-- Lead Owner -->
                                <select class="custom-select col-12" name="LEAD_OWNER">
                                    <option selected="">Choose...</option>
                                    <?php foreach ($all_users as $user) : ?>
                                        <option value="<?= $user['USER_ID'] ?>" <?= ($lead['LEAD_OWNER'] == $user['USER_ID']) ? 'selected' : '' ?>>
                                            <?= $user['NAME'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Lead Source + Lead Source Detail (dependent) -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Lead Source</label>
                            <div class="col-md-8">
                                <!-- Lead Source -->
                                <select name="LEAD_SOURCE" id="ENTITY_LEAD_SOURCE" class="form-control">
                                    <option value="-1">Select</option>
                                    <option value="1" <?= ($lead['LEAD_SOURCE'] == 1) ? 'selected' : '' ?>>Advertisement</option>
                                    <option value="2" <?= ($lead['LEAD_SOURCE'] == 2) ? 'selected' : '' ?>>Brokers</option>
                                    <option value="3" <?= ($lead['LEAD_SOURCE'] == 3) ? 'selected' : '' ?>>Friends</option>
                                    <option value="4" <?= ($lead['LEAD_SOURCE'] == 4) ? 'selected' : '' ?>>Import</option>
                                    <option value="5" <?= ($lead['LEAD_SOURCE'] == 5) ? 'selected' : '' ?>>Internet</option>
                                    <option value="6" <?= ($lead['LEAD_SOURCE'] == 6) ? 'selected' : '' ?>>Referred By</option>
                                    <option value="7" <?= ($lead['LEAD_SOURCE'] == 7) ? 'selected' : '' ?>>Search</option>
                                    <option value="8" <?= ($lead['LEAD_SOURCE'] == 8) ? 'selected' : '' ?>>Landing Page</option>
                                    <option value="69676451" <?= ($lead['LEAD_SOURCE'] == 69676451) ? 'selected' : '' ?>>Work Shop</option>
                                    <option value="11831307" <?= ($lead['LEAD_SOURCE'] == 11831307) ? 'selected' : '' ?>>Training/ Coaching Session</option>
                                    <option value="38926522" <?= ($lead['LEAD_SOURCE'] == 38926522) ? 'selected' : '' ?>>Social Media</option>
                                </select>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Lead Source Detail </label>
                            <div class="col-md-8">
                                <!-- Lead Source Detail - now dynamically filled by JS -->
                                <select name="LEAD_SOURCE_DETAIL" id="ENTITY_LEAD_SOURCE_DETAIL" class="form-control">
                                    <option value="-1">Select Lead Source First</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /Lead Source + Lead Source Detail -->

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Current Net Worth </label>
                            <div class="col-md-8">
                                <!-- Current Net Worth -->
                                <input class="form-control" type="text" name="CURRENT_NET_WORTH" placeholder="CURRENT NET WORTH" value="<?= $lead['CURRENT_NET_WORTH'] ?>" />

                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Cash Available for Investment </label>
                            <div class="col-md-8">
                                <!-- Cash Available for Investment -->
                                <input class="form-control" type="text" name="CASH_AVAILABLE_INVESTMENT" placeholder="CASH AVAILABLE INVESTMENT" value="<?= $lead['CASH_AVAILABLE_INVESTMENT'] ?>" />

                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Investment Timeframe </label>
                            <div class="col-md-8">
                                <!-- Investment Timeframe -->
                                <input class="form-control" type="text" name="INVESTMENT_TIMEFRAME" placeholder="INVESTMENT TIMEFRAME" value="<?= $lead['INVESTMENT_TIMEFRAME'] ?>" />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Background </label>
                            <div class="col-md-8">
                                <!-- Background -->
                                <input class="form-control" type="text" name="BACKGROUND" placeholder="BACKGROUND" value="<?= $lead['BACKGROUND'] ?>" />

                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Source Of Investment </label>
                            <div class="col-md-8">
                                <!-- Source Of Investment -->
                                <input class="form-control" type="text" name="SOURCE_OF_INVESTMENT" placeholder="SOURCE OF INVESTMENT" value="<?= $lead['SOURCE_OF_INVESTMENT'] ?>" />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Next Call Date </label>
                            <div class="col-md-8">

                                <!-- Next Call Date -->
                                <input class="form-control" type="date" name="NEXT_CALL_DATE" placeholder="NEXT CALL DATE" value="<?= $lead['NEXT_CALL_DATE'] ?>" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-6 offset-md-2">
                        <button type="submit" class="btn btn-warning">Update Lead</button>
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
    // mapping Lead Source -> Lead Source Detail options
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
        '5': [
            { value: '10', text: 'Website / Landing Page' },
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

    function populateLeadSourceDetail(sourceId, selectedDetail) {
        var $detailSelect = $('#ENTITY_LEAD_SOURCE_DETAIL');
        $detailSelect.empty();
        $detailSelect.append('<option value="-1">Select</option>');

        var options = leadSourceDetailsMap[sourceId] || [];
        var selectedFound = false;

        options.forEach(function (opt) {
            var selectedAttr = '';
            if (selectedDetail && selectedDetail.toString() === opt.value.toString()) {
                selectedAttr = ' selected';
                selectedFound = true;
            }
            $detailSelect.append(
                '<option value="' + opt.value + '"' + selectedAttr + '>' + opt.text + '</option>'
            );
        });

        // If there is an existing detail that is not in the map (edge case)
        if (selectedDetail && selectedDetail !== '-1' && !selectedFound) {
            $detailSelect.append(
                '<option value="' + selectedDetail + '" selected>Existing Value</option>'
            );
        }
    }

    $(document).ready(function () {
        // Country -> State Ajax (existing)
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

        // Lead Source -> Lead Source Detail binding
        var currentSource  = "<?= $lead['LEAD_SOURCE'] ?>";
        var currentDetail  = "<?= $lead['LEAD_SOURCE_DETAIL'] ?>";

        // Initial population when editing existing lead
        if (currentSource && currentSource !== '-1') {
            populateLeadSourceDetail(currentSource, currentDetail);
        }

        // On change, repopulate details
        $('#ENTITY_LEAD_SOURCE').on('change', function () {
            var sourceId = $(this).val();
            populateLeadSourceDetail(sourceId, null);
        });
    });
</script>

</html>
