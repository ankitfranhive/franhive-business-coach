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
									<h4>Test Allocation to user</h4>
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
													
						</div>
						<div class="pb-20">
							<table class="data-table table stripe hover nowrap">
                            <thead>
									<tr>
										
                    <th> User ID</th>
                    <th> User Name</th>
                    <th>Email</th>
					<th>Allocated Tests</th>
                    <th>Actions</th>
									</tr>
								</thead>
                                <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                <tr></td>
                        <td><?= $user->USER_ID ?></td>
                        <td><?= $user->NAME ?></td>
                        <td><?= $user->EMAIL ?></td>
                        <td><?= $user->EMAIL ?></td>
						             
                        <td>
                            <a href="<?= base_url('test-allocation/' . $user->USER_ID) ?>" class="btn btn-primary btn-sm">Add Test</a>
                            
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
							</table>
						</div>
					</div>
				
					
				</div>
				
			</div>
		</div>


</body>
	<?php $this->load->view('includes/footer'); ?>
</html>

