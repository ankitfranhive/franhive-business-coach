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
									<h4>Listing of all Test</h4>
								</div>
								
							</div>
							<div class="col-md-6 col-sm-12 text-right">

                            <a
										class="btn btn-warning"
										href="<?= base_url('/add-test'); ?>"
										role="button"
										
									>
										Add new Test
									</a>
								
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
											Test Id
											<!-- <div class="dt-checkbox">
												<input
													type="checkbox"
													name="select_all"
													value="1"
													id="example-select-all"
												/>
												<span class="dt-checkbox-label"></span>
											</div> -->
										</th>
										
                    <th>Test Name</th>
                    <th>Test Questions</th>
                    <th>Total Marks</th>
					<th>Start Date</th>
                    <th>Actions</th>
									</tr>
								</thead>
                                <tbody>
                <?php foreach ($tests as $tests): ?>
                    <tr>
                <tr></td>
                        <td><?= $tests->TEST_ID ?></td>
                        <td><?= $tests->TEST_NAME ?></td>
                        <td><?= $tests->TOTAL_QUESTIONS ?></td>
                        <td><?= $tests->TOTAL_MARKS ?></td>
						<td><?= $tests->TEST_START_DATE ?></td>
                        <td class="no-wrap">
						<a href="<?= base_url('add-question-test/' . $tests->TEST_ID) ?>" class="btn btn-success btn-sm">Add Questions</a>
                            <a href="<?= base_url('edit-test/' . $tests->TEST_ID) ?>" class="btn btn-primary btn-sm">Edit</a>
							<a href="<?= base_url('admin/delete-test-ajax') ?>" data-testid="<?= $tests->TEST_ID ?>" class="btn btn-danger btn-sm btn-delete-test">Delete</a>

							
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
// Assuming you are using jQuery for AJAX
$('.btn-delete-test').on('click', function(e) {
    e.preventDefault();

    var testId = $(this).data('testid');

    $.ajax({
        url: '<?= base_url('/delete-test') ?>',
        type: 'POST',
        data: { test_id: testId },
        success: function(response) {
			window.location.reload();
            // Handle success response, update UI as needed
		// 	if (response.success === true) {
        // // Show tooltip
		// 	showSuccessTooltip();
		// 	window.location.reload();
		// }
            console.log(response);
        },
        error: function(error) {
            // Handle error, show error message or take appropriate action
            console.error(error);
        }
    });
});

// Function to show success tooltip
function showSuccessTooltip() {
    // Create a tooltip
    const tooltip = tippy(document.body, {
      content: 'Success!',
      placement: 'top-end', // Display on the top right
      duration: 2000, // Duration for the tooltip to be visible (in milliseconds)
    });

    // Show the tooltip
    tooltip.show();
  }
});
</script>


