<!DOCTYPE html>
<html>
<?php $this->load->view('includes/header'); ?>



<div class="mobile-menu-overlay"></div>

<div class="main-container">
    <div class="pd-ltr-20 xs-pd-20-10">
        <div class="min-height-200px">
            <div class="page-header">
                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <div class="title">
                            <h4>Listing of all Course For Allocation</h4>
                        </div>

                    </div>
                    <div class="col-md-6 col-sm-12 text-right">

                        <!-- <a
										class="btn btn-warning"
										href="<?= base_url('/add-test'); ?>"
										role="button"
										
									>
										Add new Test
									</a> -->

                    </div>
                </div>
            </div>
            <!-- Simple Datatable start -->
            <div class="card-box mb-30">
                <div class="pd-20">
                    <!-- <h4 class="text-blue h4">Listing of all Test</h4> -->

                </div>
                <div class="pb-20">
                    <table class="data-table table stripe hover nowrap">
                        <thead>
                            <tr>
                                <th>
                                    <div class="dt-checkbox">
                                        <!-- <input type="checkbox" name="select_all" value="1" id="example-select-all" />
                                        <span class="dt-checkbox-label"></span> -->
                                         ID
                                    </div>
                                </th>

                                <th>Course Name</Title></th>
                                <th> Objective</th>
                                <th>Thumbnail Image</th>
                                <!-- <th>Start Date</th> -->
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($lessons as $tests) : ?>
                               
                                    <td><?= $tests->COURSE_ID ?></td>
                                    <td><?= $tests->NAME ?></td>
                                    <td><?= $tests->OBJECTIVE ?></td>
                                    <td><img src="<?= $tests->THUMNAIL_IMAGE ?>" style="width: 230px; height: 130px; "></td>
                                    <!-- <td><?= $tests->TEST_START_DATE ?></td> -->
                                    <td>
                                        <?php if ($tests->USER_ID != null && $tests->IS_REMOVED != 1) { ?>

                                            <button class="btn btn-danger remove-test-user" data-is-removed="1" data-mapping-id="<?= $tests->ID ?>">Remove Lesson</button>
                                            <span class="tooltip" id="tooltip_<?= $tests->ID ?>">Lesson Removed!</span>

                                        <?php } elseif ($tests->USER_ID != null) { ?>

                                            <button class="btn btn-warning remove-test-user" data-is-removed="0" data-mapping-id="<?= $tests->ID ?>">Re Assigned Lesson</button>
                                            <span class="tooltip" id="tooltip_<?= $tests->ID ?>">Lesson Reassigned!</span>


                                        <?php  } else { ?>
                                            <button class="btn btn-primary add-test-user" data-test-id="<?= $tests->COURSE_ID ?>">Assign to user</button>
                                            <span class="tooltip" id="tooltip_<?= $tests->COURSE_ID ?>">Lesson Assigned!</span>

                                        <?php  } ?>
                                        
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- Simple Datatable End -->

        </div>

    </div>
</div>


</body>
<?php $this->load->view('includes/footer'); ?>
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

<script>
    $(document).ready(function() {
        $('.add-test-user').on('click', function() {
            var button = $(this);
            var COURSE_ID = button.data('test-id');
            // alert(questionId);
            var currentUrl = window.location.href;
            var segments = currentUrl.split('/');
            var user_id = segments.pop();

            var tooltip = $('#tooltip_' + COURSE_ID);

            // Make AJAX request
            $.ajax({
                url: '<?= base_url('save-lesson-user'); ?>',
                method: 'POST',
                data: {
                    course_id: COURSE_ID,
                    user_id: user_id
                },
                success: function(response) {
                    // Hide the "Add Question" button
                    button.hide();

                    // Create and show the "Remove Question" button
                    var removeButton = $('<button>')
                        .addClass('btn btn-danger remove-question')
                        .data('test-id', COURSE_ID)
                        .text('Remove Lesson')
                        .on('click', function() {
                            // Handle removal logic here if needed
                            // For now, just hide the button
                            $(this).hide();
                        });

                    // Insert the new button after the current button
                    button.after(removeButton);

                    // Show the tooltip
                    tooltip.css('visibility', 'visible');

                    // Hide tooltip after 3 seconds
                    setTimeout(function() {
                        tooltip.css('visibility', 'hidden');
                    }, 3000);

                    window.location.reload();
                },
                error: function(error) {
                    // Handle errors
                    console.error(error);
                }
            });
        });
        $('.remove-test-user').on('click', function() {
            var button = $(this);
            var id = button.data('mapping-id');
            var is_removed = button.data('is-removed');
            // alert(questionId);
            var currentUrl = window.location.href;
            var segments = currentUrl.split('/');
            var user_id = segments.pop();

            var tooltip = $('#tooltip_' + id);

            // Make AJAX request
            $.ajax({
                url: '<?= base_url('remove-lesson-user'); ?>',
                method: 'POST',
                data: {
                    id: id,
                    user_id: user_id,
                    is_removed: is_removed
                },
                success: function(response) {
                    // Hide the "Add Question" button
                    button.hide();

                    // Create and show the "Remove Question" button
                    var removeButton = $('<button>')
                        .addClass('btn btn-danger remove-question')
                        .data('mapping-id', id)
                        .text('Remove Lesson')
                        .on('click', function() {
                            // Handle removal logic here if needed
                            // For now, just hide the button
                            $(this).hide();
                        });

                    // Insert the new button after the current button
                    button.after(removeButton);

                    // Show the tooltip
                    tooltip.css('visibility', 'visible');

                    // Hide tooltip after 3 seconds
                    setTimeout(function() {
                        tooltip.css('visibility', 'hidden');
                    }, 3000);
                    window.location.reload();
                },
                error: function(error) {
                    // Handle errors
                    console.error(error);
                }
            });
        });
    });
</script>

