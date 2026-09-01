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
									<h4>Listing of all Questions</h4>
								</div>
								
							</div>
							<div class="col-md-6 col-sm-12 text-right">

                            <a
										class="btn btn-warning"
										href="<?= base_url('/add-question'); ?>"
										role="button"
										
									>
										Add new Question
									</a>
								
							</div>
						</div>
					</div>
					<!-- Simple Datatable start -->
					<div class="card-box mb-30">
						<div class="pd-20">
							<!-- <h4 class="text-blue h4">Listing of all Questions</h4> -->
							
						</div>
						<div class="pb-20">
							<table class="data-table table stripe hover nowrap">
                            <thead>
							  <tr>
								<th>Question Id</th>
								<th>Question Name</th>
								<th>Question Group</th>
								<th>Marks</th>
								<th>Actions</th>
								</tr>
								</thead>
                                <tbody>
                <?php foreach ($questions as $question): ?>
                    <tr>
                <tr></td>
                        <td><?= $question->QUESTION_ID ?></td>
                        <td><?= $question->QUESTION_NAME ?></td>
						<td><?= isset($question->QUESTION_GROUP) ? $question->QUESTION_GROUP : "" ?></td>
                        <!-- <td><?=  ($question->QUESTION_TYPE == 1) ? "Objective" : "Subjective" ; ?></td> -->
                        <td><?= $question->QUESTION_MARKS ?></td>
                        <td>
                            <a href="<?= base_url('edit-quetsion/' . $question->QUESTION_ID) ?>" class="btn btn-primary btn-sm">Edit</a>
							<a href="<?= base_url('admin/delete_question/' . $question->QUESTION_ID) ?>" class="btn btn-danger btn-sm">Delete</a>

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

