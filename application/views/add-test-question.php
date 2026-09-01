<!DOCTYPE html>
<html>
<?php $this->load->view('includes/header'); ?>

<style>
    .tooltip {
        visibility: hidden;
        background-color: black;
        color: white;
        text-align: center;
        border-radius: 6px;
        padding: 5px 0;
        position: absolute;
        z-index: 1;
        bottom: 100%;
        left: 50%;
        margin-left: -60px;
    }
</style>


<div class="mobile-menu-overlay"></div>

<div class="main-container">
    <div class="pd-ltr-20 xs-pd-20-10">
        <div class="min-height-200px">

            <!-- Simple Datatable start -->
            <div class="card-box mb-30">
                <div class="pd-20">
                    <h4 class="text-fh h4">Listing of all Questions</h4>

                </div>
                <div class="pb-20">
                    <table class="checkbox-datatable table nowrap">
                        <thead>
                            <tr>
                                <th>
                                    <div class="dt-checkbox">
                                        <input type="checkbox" name="select_all" value="1" id="example-select-all" />
                                        <span class="dt-checkbox-label"></span>
                                    </div>
                                </th>

                                <th>Question Name</th>
                                <th>Question Group</th>

                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($questions as $question) : ?>
                                <tr>
                                    <td><input type="checkbox" name="select_all" value="1" id="example-select-all" />
                                        <!-- <span class="dt-checkbox-label"></span></td> -->

                                    <td><?= $question->QUESTION_NAME ?></td>

                                    <td><?= $question->QUESTION_GROUP ?></td>

                                    <td>
                                        <button class="btn btn-primary add-question" data-question-id="<?= $question->QUESTION_ID ?>">Add Question</button>
                                        <span class="tooltip" id="tooltip_<?= $question->QUESTION_ID ?>">Question Added!</span>
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
</div>

</body>

<script src="<?= base_url('vendors/scripts/core.js'); ?>"></script>
<script src="<?= base_url('vendors/scripts/script.min.js'); ?>"></script>
<script src="<?= base_url('vendors/scripts/process.js'); ?>"></script>
<script src="<?= base_url('vendors/scripts/layout-settings.js'); ?>"></script>
<!-- <script src="<?= base_url('src/plugins/apexcharts/apexcharts.min.js'); ?>"></script> -->
<script src="<?= base_url('src/plugins/datatables/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?= base_url('src/plugins/datatables/js/dataTables.bootstrap4.min.js'); ?>"></script>
<script src="<?= base_url('src/plugins/datatables/js/dataTables.responsive.min.js'); ?>"></script>
<script src="<?= base_url('src/plugins/datatables/js/responsive.bootstrap4.min.js'); ?>"></script>
<!-- <script src="<?= base_url('vendors/scripts/dashboard3.js'); ?>"></script> -->

<script>
    $(document).ready(function() {
        $('.add-question').on('click', function() {
            var button = $(this);
            var questionId = button.data('question-id');
            // alert(questionId);
            var currentUrl = window.location.href;
            var segments = currentUrl.split('/');
            var test_id = segments.pop();

            var tooltip = $('#tooltip_' + questionId);

            // Make AJAX request
            $.ajax({
                url: '<?= base_url('save-question-test'); ?>',
                method: 'POST',
                data: {
                    question_id: questionId,
                    test_id: test_id
                },
                success: function(response) {
                    // Hide the "Add Question" button
                    button.hide();

                    // Create and show the "Remove Question" button
                    var removeButton = $('<button>')
                        .addClass('btn btn-danger remove-question')
                        .data('question-id', questionId)
                        .text('Remove Question')
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
                },
                error: function(error) {
                    // Handle errors
                    console.error(error);
                }
            });
        });
    });
</script>

</html>