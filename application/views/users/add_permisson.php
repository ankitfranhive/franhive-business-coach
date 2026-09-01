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
                        <h4>Add A new Permission</h4>
                    </div>
                </div>
                <div class="col-md-6 col-sm-12 text-right">
                    <a
                        class="btn btn-warning"
                        href="<?= base_url('/user-listing'); ?>"
                        role="button"
                    >
                        Back To Permissions List
                    </a>
                </div>
            </div>
        </div>
        <div class="pd-20 card-box mb-30">
            <div class="clearfix">
                <div class="pull-left">
                    <!-- <h4 class="text-blue h4">Add A new Question</h4> -->
                </div>
            </div>
            <form action="<?= base_url('usercontroller/create_permisson') ?>" method="post">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">User type<span style="color: red;">*</span></label>
                            <div class="col-md-8">
                                <select class="custom-select col-12" name="JOB_TITLE">
                                    <option selected="">Choose...</option>
                                    <option value="2">Mr.</option>
                                    <option value="1">Mrs.</option>
                                    <option value="1">Dr.</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Permission Name <span style="color: red;">*</span></label>
                            <div class="col-md-8">
                                <input
                                    class="form-control"
                                    type="text"
                                    name="NAME"
                                    placeholder="User Name"
                                />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Collapsible Row -->
                <div class="row">
                    <div class="col-md-12">
                    <div class="card" onclick="toggleModulePermissions(event)">

                            <div class="card-header" id="headingOne">
                                <h5 class="mb-0">
                                    <button class="btn btn-link" aria-expanded="false">
                                        <span id="moduleToggleIcon">+</span> Module Permissions
                                    </button>
                                </h5>
                            </div>
                            <div id="modulePermissions" style="display: none;">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label>Select Modules:</label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="1" id="module1" name="modules[]">
                                            <label class="form-check-label" for="module1">
                                                Module 1
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="2" id="module2" name="modules[]">
                                            <label class="form-check-label" for="module2">
                                                Module 2
                                            </label>
                                        </div>
                                        <!-- Add more modules as needed -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Collapsible Row -->

                <div class="form-group row">
                    <div class="col-md-6 offset-md-2">
                        <button type="submit" class="btn btn-warning">Add User</button>
                    </div>
                </div>
            </form>
        </div>
        <!-- Default Basic Forms End -->
    </div>
</div>
<?php $this->load->view('includes/footer'); ?>

<script>
    function toggleModulePermissions(event) {
    event.stopPropagation(); // Prevent the click event from propagating
    var modulePermissions = document.getElementById("modulePermissions");
    var icon = document.getElementById("moduleToggleIcon");
    if (modulePermissions.style.display === "none") {
        modulePermissions.style.display = "block";
        icon.innerText = "-";
    } else {
        modulePermissions.style.display = "none";
        icon.innerText = "+";
    }
}

</script>

</html>
