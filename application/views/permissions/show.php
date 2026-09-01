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
						<h4>Permission</h4>
					</div>

				</div>
				<div class="col-md-6 col-sm-12 text-right">
					<a class="btn btn-warning" href="<?= base_url('/permissions'); ?>" permission="button">
						Back To Permissions Listing
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
			<!-- <form action="<?= base_url('add-permission') ?>" method="post"> -->
			<div class="col-md-6">
				<div class="form-group row">
					<label class="col-sm-12 col-md-2 col-form-label">Permission Name</label>
					<div class="col-sm-12 col-md-10">
						<input class="form-control" type="text" name="permission_name" placeholder="Permission Name" value=<?= $permission['PERMISSION_NAME'] ?? '' ?> readonly />
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group row">
					<label class="col-form-label col-md-4">Module<span style="color: red;">*</span></label>
					<div class="col-md-8">
						<?= $permission['MODULE_NAME'] ?>
					</div>
				</div>
			</div>

			<!-- <div class="form-group row"> -->
			<!-- <label class="col-sm-12 col-md-2 col-form-label">MarksL</label> -->
			<!-- <div class="col-sm-6 col-md-4"> -->

			<!-- <button type="submit" class="btn btn-warning">Add Permission</button> -->
			<!-- </div> -->
			<!-- </div> -->

			<!-- </form> -->

		</div>
		<!-- Default Basic Forms End -->


	</div>
</div>


</body>
<?php $this->load->view('includes/footer'); ?>

</html>