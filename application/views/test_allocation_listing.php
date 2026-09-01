<!DOCTYPE html>
<html>
<?php $this->load->view('includes/header'); ?>

<style>
    .data-table td {
    white-space: normal !important; /* Allow wrapping */
    word-break: break-word;         /* Break long words */
}
.pb-20 {
    overflow-x: auto;
}

</style>

<div class="mobile-menu-overlay"></div>

<div class="main-container">
    <div class="pd-ltr-20 xs-pd-20-10">
        <div class="min-height-200px">
            <div class="page-header">
                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <div class="title">
                            <h4>Listing of all Test For Allocation</h4>
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
                                    Sr No.
                                </th>

                                <th>Test Name</th>
                                <th>Test Questions</th>
                                <th>Total Marks</th>
                                <th>Start Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($tests as $tests) : ?>
                                <tr>
                                <tr>
                                    </td>
                                    <td><?= $tests->TEST_ID ?></td>
                                    <td><?= $tests->TEST_NAME ?></td>
                                    <td><?= $tests->TOTAL_QUESTIONS ?></td>
                                    <td><?= $tests->TOTAL_MARKS ?></td>
                                    <td><?= $tests->TEST_START_DATE ?></td>
                                    <td>
                                        <?php if ($tests->USER_ID != null && $tests->IS_REMOVED != 1) { ?>

                                            <button class="btn btn-danger remove-test-user" data-is-removed="1" data-mapping-id="<?= $tests->MAPPING_ID ?>">Remove Test</button>
                                            <span class="tooltip" id="tooltip_<?= $tests->MAPPING_ID ?>">Test Removed!</span>

                                        <?php } elseif ($tests->USER_ID != null) { ?>

                                            <button class="btn btn-warning remove-test-user" data-is-removed="0" data-mapping-id="<?= $tests->MAPPING_ID ?>">Re Assigned Test</button>
                                            <span class="tooltip" id="tooltip_<?= $tests->MAPPING_ID ?>">Test Reassigned!</span>


                                        <?php  } else { ?>
                                            <button class="btn btn-primary add-test-user" data-test-id="<?= $tests->TEST_ID ?>">Assign to user</button>
                                            <span class="tooltip" id="tooltip_<?= $tests->TEST_ID ?>">Test Assigned!</span>

                                        <?php  } ?>
                                        <!-- <button
                        class="btn btn-primary add-test-user"
                        data-test-id="<?= $tests->TEST_ID ?>"
                    >Assign to User</button>
                    <span class="tooltip" id="tooltip_<?= $tests->TEST_ID ?>">Test Assigned!</span> -->

                                        <!-- <a href="<?= base_url('save-test-user/' . $tests->TEST_ID) ?>" class="btn btn-success btn-sm add-test-user">Assign to User</a> -->
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

</html>

<script>
    $(document).ready(function() {
        $('.add-test-user').on('click', function() {
            var button = $(this);
            var testId = button.data('test-id');
            // alert(questionId);
            var currentUrl = window.location.href;
            var segments = currentUrl.split('/');
            var user_id = segments.pop();

            var tooltip = $('#tooltip_' + testId);

            // Make AJAX request
            $.ajax({
                url: '<?= base_url('save-test-user'); ?>',
                method: 'POST',
                data: {
                    test_id: testId,
                    user_id: user_id
                },
                success: function(response) {
                    // Hide the "Add Question" button
                    button.hide();

                    // Create and show the "Remove Question" button
                    var removeButton = $('<button>')
                        .addClass('btn btn-danger remove-question')
                        .data('test-id', testId)
                        .text('Remove Test')
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
            var mapping_id = button.data('mapping-id');
            var is_removed = button.data('is-removed');
            // alert(questionId);
            var currentUrl = window.location.href;
            var segments = currentUrl.split('/');
            var user_id = segments.pop();

            var tooltip = $('#tooltip_' + mapping_id);

            // Make AJAX request
            $.ajax({
                url: '<?= base_url('remove-test-user'); ?>',
                method: 'POST',
                data: {
                    mapping_id: mapping_id,
                    user_id: user_id,
                    is_removed: is_removed
                },
                success: function(response) {
                    // Hide the "Add Question" button
                    button.hide();

                    // Create and show the "Remove Question" button
                    var removeButton = $('<button>')
                        .addClass('btn btn-danger remove-question')
                        .data('mapping-id', mapping_id)
                        .text('Remove Test')
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