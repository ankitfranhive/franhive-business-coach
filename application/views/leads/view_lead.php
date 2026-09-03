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
                        <h4>View Lead Details</h4>
                    </div>
                </div>
                <div class="col-md-6 col-sm-12 text-right">
                    <a
                        class="btn btn-warning"
                        href="<?= base_url('/leads'); ?>"
                        role="button"
                    >
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
            <h5>Personal Details</h5>
            <br>
            <input type="hidden" name="id" value="<?= $lead['ENTITY_ID'] ?>">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-form-label col-md-4">Job Title <span style="color: red;">*</span></label>
                        <div class="col-md-8">
                            <p class="form-control-static">
                                <?php
                                switch ($lead['TITLE']) {
                                    case 1:
                                        echo "Dr.";
                                        break;
                                    case 2:
                                        echo "Miss";
                                        break;
                                    case 3:
                                        echo "Mr.";
                                        break;
                                    case 4:
                                        echo "Mrs.";
                                        break;
                                    case 5:
                                        echo "Ms.";
                                        break;
                                    default:
                                        echo "Select";
                                }
                                ?>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-form-label col-md-4">User Name <span style="color: red;">*</span></label>
                        <div class="col-md-8">
                            <p class="form-control-static"><?= $lead['NAME'] ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-form-label col-md-4">Email <span style="color: red;">*</span></label>
                        <div class="col-md-8">
                            <p class="form-control-static"><?= $lead['EMAIL'] ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-form-label col-md-4">Mobile <span style="color: red;">*</span></label>
                        <div class="col-md-8">
                            <p class="form-control-static"><?= $lead['PHONE'] ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-form-label col-md-4">Business type<span style="color: red;">*</span></label>
                        <div class="col-md-8">
                            <p class="form-control-static">
                                <?php
                                switch ($lead['BUISNESS']) {
                                    case 52657110:
                                        echo "Event Management";
                                        break;
                                    default:
                                        echo "Select";
                                }
                                ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-form-label col-md-4">Preferred Mode of Contact <span style="color: red;">*</span></label>
                        <div class="col-md-8">
                            <p class="form-control-static">
                                <?php
                                switch ($lead['PREF_MODE_CONTACT']) {
                                    case 1:
                                        echo "Work Phone";
                                        break;
                                    case 2:
                                        echo "Home Phone";
                                        break;
                                    case 3:
                                        echo "Fax";
                                        break;
                                    case 4:
                                        echo "Mobile";
                                        break;
                                    case 5:
                                        echo "Email";
                                        break;
                                    default:
                                        echo "Select";
                                }
                                ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-form-label col-md-4">Best Time To Contact <span style="color: red;">*</span></label>
                        <div class="col-md-8">
                            <p class="form-control-static"><?= $lead['BEST_TIME_CONTACT'] ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-form-label col-md-4">Work Phone <span style="color: red;">*</span></label>
                        <div class="col-md-8">
                            <p class="form-control-static"><?= $lead['HOME_PHONE'] ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-form-label col-md-2">Address line 1 <span style="color: red;">*</span></label>
                <div class="col-md-10">
                    <p class="form-control-static"><?= $lead['ADDRESS_LINE_1'] ?></p>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-form-label col-md-2">Address Line 2 <span style="color: red;">*</span></label>
                <div class="col-md-10">
                    <p class="form-control-static"><?= $lead['ADDRESS_LINE_2'] ?></p>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-form-label col-md-4">City <span style="color: red;">*</span></label>
                        <div class="col-md-8">
                            <p class="form-control-static"><?= $lead['CITY'] ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-form-label col-md-4">Zip <span style="color: red;">*</span></label>
                        <div class="col-md-8">
                            <p class="form-control-static"><?= $lead['ZIP'] ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-form-label col-md-4">Country <span style="color: red;">*</span></label>
                        <div class="col-md-8">
                            <p class="form-control-static"><?= $lead['COUNTRY'] ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-form-label col-md-4">State <span style="color: red;">*</span></label>
                        <div class="col-md-8">
                            <p class="form-control-static"><?= $lead['STATE'] ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-form-label col-md-2">Comments </label>
                <div class="col-md-10">
                    <p class="form-control-static"><?= $lead['COMMENTS'] ?></p>
                </div>
            </div>

            <div style="border-bottom: 1px dotted #000;"></div>
            <br>

            <h5>Lead Details</h5>
            <br>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-form-label col-md-4">Lead Status <span style="color: red;">*</span></label>
                        <div class="col-md-8">
                            <p class="form-control-static">
                                <?php
                                switch ($lead['LEAD_STATUS']) {
                                    case 1:
                                        echo "New";
                                        break;
                                    case 2:
                                        echo "Working";
                                        break;
                                    case 3:
                                        echo "Nurturing";
                                        break;
                                    case 4:
                                        echo "Qualified";
                                        break;
                                    case 5:
                                        echo "Disqualified";
                                        break;
                                    default:
                                        echo "Select";
                                }
                                ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-form-label col-md-4">Lead Source <span style="color: red;">*</span></label>
                        <div class="col-md-8">
                            <p class="form-control-static">
                                <?php
                                switch ($lead['LEAD_SOURCE']) {
                                    case 1:
                                        echo "Cold Call";
                                        break;
                                    case 2:
                                        echo "Existing Customer";
                                        break;
                                    case 3:
                                        echo "Self Generated";
                                        break;
                                    case 4:
                                        echo "Employee";
                                        break;
                                    case 5:
                                        echo "Partner";
                                        break;
                                    case 6:
                                        echo "Public Relations";
                                        break;
                                    case 7:
                                        echo "Direct Mail";
                                        break;
                                    case 8:
                                        echo "Conference";
                                        break;
                                    case 9:
                                        echo "Trade Show";
                                        break;
                                    case 10:
                                        echo "Website";
                                        break;
                                    case 11:
                                        echo "Word of mouth";
                                        break;
                                    case 12:
                                        echo "Email";
                                        break;
                                    case 13:
                                        echo "Campaign";
                                        break;
                                    default:
                                        echo "Select";
                                }
                                ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>



            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-form-label col-md-4">Lead Sourse Details </label>
                        <div class="col-md-8">
    <p class="form-control-static">
        <?php
        switch ($lead['LEAD_SOURCE_DETAIL']) {
            case -1:
                echo "Select";
                break;
            case 1:
                echo "Magzine";
                break;
            case 2:
                echo "News Paper";
                break;
            case 3:
                echo "Other";
                break;
            case 4:
                echo "Trade Shows";
                break;
            case 5:
                echo "Friends";
                break;
            case 6:
                echo "Google";
                break;
            case 7:
                echo "Bing";
                break;
            case 8:
                echo "Other";
                break;
            case 9:
                echo "Yahoo";
                break;
            case 10:
                echo "Landing Page";
                break;
            case 38768978:
                echo "LEAD YOUR LEGACY (May 2023)";
                break;
            case 30150936:
                echo "Change your Life and Business Training";
                break;
            case 38283371:
                echo "Facebook";
                break;
            case 8998608:
                echo "Instagram";
                break;
            case 59011397:
                echo "LinkedIn";
                break;
            default:
                echo "Unknown";
        }
        ?>
    </p>
</div>

                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-form-label col-md-4">Lead Owner <span style="color: red;">*</span></label>
                        <div class="col-md-8">
                            <p class="form-control-static">
                                <?php
                                switch ($lead['LEAD_SOURCE']) {
                                    case 1:
                                        echo "Cold Call";
                                        break;
                                    case 2:
                                        echo "Existing Customer";
                                        break;
                                    case 3:
                                        echo "Self Generated";
                                        break;
                                    case 4:
                                        echo "Employee";
                                        break;
                                    case 5:
                                        echo "Partner";
                                        break;
                                    case 6:
                                        echo "Public Relations";
                                        break;
                                    case 7:
                                        echo "Direct Mail";
                                        break;
                                    case 8:
                                        echo "Conference";
                                        break;
                                    case 9:
                                        echo "Trade Show";
                                        break;
                                    case 10:
                                        echo "Website";
                                        break;
                                    case 11:
                                        echo "Word of mouth";
                                        break;
                                    case 12:
                                        echo "Email";
                                        break;
                                    case 13:
                                        echo "Campaign";
                                        break;
                                    default:
                                        echo "Select";
                                }
                                ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('includes/footer'); ?>
</html>
