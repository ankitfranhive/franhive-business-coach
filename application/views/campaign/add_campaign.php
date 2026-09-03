<!DOCTYPE html>
<html lang="en">

<head>
    <?php $this->load->view('includes/header'); ?>
    <style>
        /* Progress bar container */
        .progress-container {
            position: relative;
            width: 100%;
            height: 4px;
            background-color: #f1f1f1;
            z-index: 9999;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Progress bar */
        .progress-bar {
            height: 100%;
            background-color: #4CAF50;
            width: 0;
        }

        /* Dots */
        .dot {
            height: 8px;
            width: 8px;
            background-color: #bbb;
            border-radius: 50%;
            margin: 0 -2px;
        }

        .dot.active {
            background-color: #4CAF50;
        }
    </style>
</head>

<body>
    <div class="mobile-menu-overlay"></div>

    <div class="main-container">
        <div class="pd-ltr-20 xs-pd-20-10">
            <div class="page-header">
                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <div class="title">
                            <h4>Add A New Campaign</h4>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12 text-right">
                        <a class="btn btn-warning" href="<?= base_url('/campaigns'); ?>" role="button">Back To Campaign List</a>
                    </div>
                </div>
            </div>

            <div class="pd-20 card-box mb-30">
                <form id="campaignForm" action="<?= base_url('CampaignController/create_campaign') ?>" method="post">
                    <!-- Screen 1 -->
                    <div id="screen1" class="screen">
                        <div class="form-group row">
                            <label class="col-sm-12 col-md-2 col-form-label">Name this campaign</label>
                            <div class="col-sm-12 col-md-10">
                                <input class="form-control" type="text" name="TITLE" placeholder="Campaign title" />
                                <i>
                                    <p>The campaign name is shown in your reports and your email archive.</p>
                                </i>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-12 col-md-2 col-form-label">Who is it from?</label>
                            <div class="col-sm-12 col-md-10">
                                <div class="row">
                                    <div class="col-md-6">
                                        <input class="form-control" name="MANAGER_NAME" type="text" placeholder="Name" />
                                        <i>
                                            <p>This will display in the From field.</p>
                                        </i>
                                    </div>
                                    <div class="col-md-6">
                                        <input class="form-control" value="NLP@empoweryourdestiny.com.au" name="REPLY_ADDRESS" type="text" disabled />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-12 col-md-2 col-form-label">Create campaign for</label>
                            <div class="col-sm-12 col-md-10">
                                <select class="custom-select col-12" name="MODULE_NAME">
                                    <option selected>Choose...</option>
                                    <option value="Lead">Lead</option>
                                    <option value="Client">Client</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-6 col-md-4">
                                <button type="button" class="btn btn-warning" onclick="nextScreen()">Next</button>
                            </div>
                        </div>
                    </div>

                    <!-- Screen 2 -->
                    <div id="screen2" class="screen" style="display: none;">
                        <h4>Select Templates</h4>
                        <div class="pb-20">
                            <table class="data-table table stripe hover nowrap">
                                <thead>
                                    <tr>
                                        <th>Select</th>
                                        <th> ID</th>
                                        <th>Template Name</th>
                                        <th>Module Name</th>
                                        <th>Template Subject</th>
                                        <th>Sending Order</th>
                                        <th>Send Date</th>
                                        <th>Start Time</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($all_templates as $template): ?>
                                        <tr>
                                            <td>
                                                <input type="checkbox" name="TEMPLATE_IDS[]" value="<?= $template['TEMPLATE_ID'] ?>">
                                            </td>
                                            <td><?= $template['TEMPLATE_ID'] ?></td>
                                            <td><?= $template['TEMPLATE_NAME'] ?></td>
                                            <td><?= $template['MODULE_NAME'] ?></td>
                                            <td><?= $template['TEMPLATE_SUBJECT'] ?></td>
                                            <td>
                                                <input type="number" placeholder="Ender Order Number" name="SENDING_ORDER[<?= $template['TEMPLATE_ID'] ?>]" min="1" class="form-control">
                                            </td>
                                            <td>
                                                <input type="date" name="SEND_DATE[<?= $template['TEMPLATE_ID'] ?>]" class="form-control">
                                            </td>
                                            <td>
                                                <input type="time" name="TEMPLATE_START_TIME[<?= $template['TEMPLATE_ID'] ?>]" class="form-control">
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>

                            </table>
                        </div>
                        <button type="button" class="btn btn-warning" onclick="prevScreen()">Back</button>
                        <button type="button" class="btn btn-warning" onclick="nextScreen()">Next</button>
                    </div>

                    <!-- Screen 3 -->
                    <div id="screen3" class="screen" style="display: none;">
                        <h4>Select Contacts</h4>
                        <div class="pb-20">
                            <table class="data-table table stripe hover nowrap" id="contactsTable">
                                <thead>
                                    <tr>
                                        <th>Select</th>
                                        <th> ID</th>
                                        <th>CLIENT Name</th>
                                        <th>PHONE</th>
                                        <th>User Email</th>
                                        <th>IS LEAD</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($all_users as $user): ?>
                                        <tr>
                                            <td>
                                                <input type="checkbox" name="CONTACT_IDS[]" value="<?= $user['ENTITY_ID'] ?>">
                                            </td>
                                            <td><?= $user['ENTITY_ID'] ?></td>
                                            <td><?= $user['NAME'] ?></td>
                                            <td><?= $user['MOBILE'] ?></td>
                                            <td><?= $user['EMAIL'] ?></td>
                                            <td><?= ($user['IS_LEAD']=='Y')?'LEAD':'CLIENT' ?></td>

                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <button type="button" class="btn btn-warning" onclick="prevScreen()">Back</button>
                        <button type="button" class="btn btn-warning" onclick="nextScreen()">Next</button>
                    </div>

                    <!-- Screen 4 -->
                    <div id="screen4" class="screen" style="display: none;">
                        <div class="form-group row">
                            <label class="col-sm-12 col-md-2 col-form-label">Start Date</label>
                            <div class="col-sm-12 col-md-10">
                                <input class="form-control " name="START_DATE" placeholder="Choose Date and time" type="date">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-12 col-md-2 col-form-label">End Date</label>
                            <div class="col-sm-12 col-md-10">
                                <input class="form-control " name="END_DATE" placeholder="Choose Date and time" type="date">
                            </div>
                        </div>
                        <button type="button" class="btn btn-warning" onclick="prevScreen()">Back</button>
                        <button type="button" class="btn btn-warning" onclick="submitForm()">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php $this->load->view('includes/footer'); ?>
    <script>
        var currentScreen = 1;

        function nextScreen() {
            if (currentScreen < 4) {
                document.getElementById('screen' + currentScreen).style.display = 'none';
                currentScreen++;
                document.getElementById('screen' + currentScreen).style.display = 'block';
                updateProgressBar();
            }
        }

        function prevScreen() {
            if (currentScreen > 1) {
                document.getElementById('screen' + currentScreen).style.display = 'none';
                currentScreen--;
                document.getElementById('screen' + currentScreen).style.display = 'block';
                updateProgressBar();
            }
        }

        function submitForm() {
            document.getElementById('campaignForm').submit();
        }

        function updateProgressBar() {
            var progress = (currentScreen - 1) * 33.33;
            document.getElementById('progressBar').style.width = progress + '%';

            var dots = document.querySelectorAll('.dot');
            dots.forEach((dot, index) => {
                if (index === currentScreen - 1) {
                    dot.classList.add('active');
                } else {
                    dot.classList.remove('active');
                }
            });
        }
    </script>
</body>
<script src="src/plugins/datatables/js/jquery.dataTables.min.js"></script>
<script src="src/plugins/datatables/js/dataTables.bootstrap4.min.js"></script>
<script src="src/plugins/datatables/js/dataTables.responsive.min.js"></script>
<script src="src/plugins/datatables/js/responsive.bootstrap4.min.js"></script>
<!-- buttons for Export datatable -->
<script src="src/plugins/datatables/js/dataTables.buttons.min.js"></script>
<script src="src/plugins/datatables/js/buttons.bootstrap4.min.js"></script>
<script src="src/plugins/datatables/js/buttons.print.min.js"></script>
<script src="src/plugins/datatables/js/buttons.html5.min.js"></script>
<script src="src/plugins/datatables/js/buttons.flash.min.js"></script>
<script src="src/plugins/datatables/js/pdfmake.min.js"></script>
<script src="src/plugins/datatables/js/vfs_fonts.js"></script>
<!-- Datatable Setting js -->
<script src="vendors/scripts/datatable-setting.js"></script>
</html>