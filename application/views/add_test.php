<!DOCTYPE html>
<html>
<?php $this->load->view('includes/header'); ?>

<script src="<?php echo base_url('vendor/tinymce/tinymce/tinymce.min.js'); ?>"></script>
<script type="text/javascript">
   $(function() {
      tinymce.init({
         selector: 'textarea',
         plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount checklist mediaembed casechange export formatpainter pageembed linkchecker a11ychecker tinymcespellchecker permanentpen powerpaste advtable advcode editimage advtemplate ai mentions tinycomments tableofcontents footnotes mergetags autocorrect typography inlinecss markdown',
         toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck typography | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
         tinycomments_mode: 'embedded',
         tinycomments_author: 'Author name',
         branding: false,
         setup: function(editor) {
            editor.on('change', function() {
               tinymce.triggerSave(); // Update the textarea with TinyMCE content
            });
         }
      });
   });
</script>

<div class="mobile-menu-overlay"></div>

<div class="main-container">
	<div class="pd-ltr-20 xs-pd-20-10">
		<div class="page-header">
			<div class="row">
				<div class="col-md-6 col-sm-12">
					<div class="title">
						<h4>Add A new Test</h4>
					</div>

				</div>
				<div class="col-md-6 col-sm-12 text-right">

					<a class="btn btn-warning" href="<?= base_url('/test'); ?>" role="button">
						Back To Test List
					</a>

				</div>
			</div>
		</div>
		<div class="pd-20 card-box mb-30">
			<div class="clearfix">
				<div class="pull-left">
					<!-- <h4 class="text-fh h4">Add A new Test</h4> -->

				</div>

			</div>
			
			<form action="<?= base_url('admin/save_test') ?>" method="post">


			<div class="form-group row">
					<label class="col-sm-12 col-md-2 col-form-label">Test Type</label>
					<div class="col-sm-12 col-md-10">
					<select class="form-control" name="test_type" placeholder="Test Type">

						<option value="1">NLP MCQ</option>
						<option value="2">Subjective</option>
						<option value="3">NORMAL MCQ</option>	
						<option value="4">BRS</option>
						<option value="5">K-10</option>
					
						
					</select>
				</div>

				</div>

				<div class="form-group row">
					<label class="col-sm-12 col-md-2 col-form-label">Test Title</label>
					<div class="col-sm-12 col-md-10">
						<input class="form-control" type="text" name="test_title" placeholder="Test title" />
					</div>
				</div>

				<div class="form-group row">
					<label class="col-sm-12 col-md-2 col-form-label">Total Questions</label>
					<div class="col-sm-12 col-md-10">
						<input class="form-control" value="" name="total_questions" type="Number" />
					</div>
				</div>

				<div class="form-group row">
					<label class="col-sm-12 col-md-2 col-form-label">Total Marks</label>
					<div class="col-sm-12 col-md-10">
						<input class="form-control" value="" name="total_marks" type="Number" />
					</div>
				</div>

				<div class="form-group row">
					<label class="col-sm-12 col-md-2 col-form-label">Total Duration (minutes):</label>

					<div class="col-sm-12 col-md-10">
						<input class="form-control" value="" name="duration" type="Number" />
					</div>


				</div>


				<div class="form-group row">
				<label class="col-sm-12 col-md-2 col-form-label">Test Instructions</label>
				<div class="col-sm-12 col-md-10">
				<textarea class="form-control" name="test_instructions" id="ckeditor" placeholder="Test Instructions" required>
                                  
                                </textarea>	

					
				</div>
			</div>





				<div class="form-group row">
					<label class="col-sm-12 col-md-2 col-form-label">Start Date</label>
					<div class="col-sm-12 col-md-10">
						<input class="form-control datetimepicker" name="start_date" placeholder="Choose Date anf time" type="text">
						<!-- <input type="datetime-local" class="form-control" id="datetimePicker" name="datetimePicker"> -->

					</div>
				</div>
				<div class="form-group row">
					<label class="col-sm-12 col-md-2 col-form-label">End Date</label>
					<div class="col-sm-12 col-md-10">
						<input class="form-control datetimepicker" name="end_date" placeholder="Choose Date anf time" type="text">
						<!-- <input type="datetime-local" class="form-control" id="datetimePicker" name="datetimePicker"> -->

					</div>
				</div>

				<div class="form-group row">
					<!-- <label class="col-sm-12 col-md-2 col-form-label">MarksL</label> -->
					<div class="col-sm-6 col-md-4">

						<button type="submit" class="btn btn-warning">Add Test</button>
					</div>
				</div>

			</form>

		</div>
		<!-- Default Basic Forms End -->


	</div>
</div>


</body>
<?php $this->load->view('includes/footer'); ?>

</html>