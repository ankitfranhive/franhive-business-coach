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
									<h4>Add A new Role</h4>
								</div>
								
							</div>
							<div class="col-md-6 col-sm-12 text-right">

                            <a
										class="btn btn-warning"
										href="<?= base_url('/roles'); ?>"
										role="button"
										
									>
									Back To Roles Listing
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
                        <form action="<?= base_url('add-role') ?>" method="post">
						<input
										class="form-control"
										type="hidden"
                                        name="permissions"
										value=""
									/>
							<div class="form-group row">
								<label class="col-sm-12 col-md-2 col-form-label">Role Name</label>
								<div class="col-sm-12 col-md-10">
									<input
										class="form-control"
										type="text"
                                        name="role_name"
										placeholder="Role Name"
									/>
								</div>
							</div>
							<div class="accordion" id="accordionPermission">
								<?php foreach($module_with_permissions as $module => $module_permissions): ?>
  								<div class="card">
									<?php 
										$module_name = explode('__',$module)[0];
										$module_id = explode('__',$module)[1];
									?>
									<div class="card-header" id="<?='heading'.$module_id?>">
									<h2 class="mb-0">
									</h2>
									<input class="module_checkbox" value="<?='module_'.$module_id?>" type="checkbox">
										<label  data-toggle="collapse" data-target="#<?='collapse'.$module_id?>" aria-expanded="true" aria-controls="<?='collapse'.$module_id?>"><?=$module_name?></label>
									</div>

									<div id="<?='collapse'.$module_id?>" class="collapse show" aria-labelledby="<?='heading'.$module_id?>" data-parent="#accordionPermission">
									<div class="card-body">
										<?php foreach($module_permissions as $permission):?>
										<input class="permission_checkbox" value="<?=$permission['PERMISSION_ID']?>" type="checkbox">
										<label><?=$permission['PERMISSION_NAME']?></label>
										<br>
										<?php endforeach; ?>
									</div>
									</div>
								</div>
								<?php endforeach; ?>
							</div>

                            <div class="form-group row">
								<!-- <label class="col-sm-12 col-md-2 col-form-label">MarksL</label> -->
								<div class="col-sm-6 col-md-4">
									
                                    <button type="submit" class="btn btn-warning">Add Role</button>
								</div>
							</div>
							
						</form>
                      
					</div>
					<!-- Default Basic Forms End -->
		</div>
    </div>

	<script>
$(document).ready(function() {
    var permissions = {};

    // Checkbox change event for modules
    $(".module_checkbox").change(function() {
        var moduleId = $(this).val();
        permissions[moduleId] = {};
        if ($(this).is(":checked")) {
            $(this).closest(".card").find(".permission_checkbox").prop("checked", true).each(function() {
                var permissionId = $(this).val();
                permissions[moduleId][permissionId] = true;
            });
        } else {
            delete permissions[moduleId];
            $(this).closest(".card").find(".permission_checkbox").prop("checked", false);
        }
		console.log(permissions);
		updateHiddenInput();
    });

    // Checkbox change event for permissions
    $(".permission_checkbox").change(function() {
        var moduleId = $(this).closest(".card").find(".module_checkbox").val();
        var permissionId = $(this).val();
        if ($(this).is(":checked")) {
            if (!permissions[moduleId]) {
                permissions[moduleId] = {};
            }
            permissions[moduleId][permissionId] = true;
        } else {
            delete permissions[moduleId][permissionId];
            if ($.isEmptyObject(permissions[moduleId])) {
                delete permissions[moduleId];
                $(this).closest(".card").find(".module_checkbox").prop("checked", false);
            }
        }
		console.log(permissions);
		updateHiddenInput();
    });
	function updateHiddenInput() {
        $("input[name='permissions']").val(JSON.stringify(permissions));
    }
});
</script>
</body>
	<?php $this->load->view('includes/footer'); ?>
</html>

