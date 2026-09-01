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
                            <h4>Videos</h4>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12 text-right">
                        <a class="btn btn-warning" href="<?= base_url('/landing-pages'); ?>" role="button">
                            Back To Pages List
                        </a>
                        <a class="btn btn-warning" href="<?= base_url('/add-video-page/' . $LANDING_PAGE_ID . ''); ?>" role="button">
                            Add new video
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

                                <th>ID</th>
                                <th>TITLE</th>
                                <th>URL</th>
                                <!-- <th>LEAD STATUS</th>
                                <th>LEAD OWNER</th>
                                <th>EMAIL</th>
                                <th>PHONE</th> -->
                                <!-- <th>CITY</th>
								<th>STATE</th>
								<th>COUNTRY</th> -->
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($videos_data as $data) : ?>

                                <tr>
                                <tr>
                                    </td>
                                    <td><?= $data['VIDEO_ID'] ?></td>
                                    <td><?= $data['VIDEO_TITLE'] ?></td>
                                    <td><?= $data['VIDEO_TEXT'] ?></td>
                                    <td>
                                        <div class="dropdown">
                                            <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                                                <i class="dw dw-more"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                                <a class="dropdown-item" href="<?php echo base_url('LandingPageController/viewVideo/' . $data['VIDEO_ID'] . ''); ?>"><i class="dw dw-eye"></i> VIEW</a>
                                                <a class="dropdown-item" href="<?php echo base_url('LandingPageController/updateVideo/' . $data['VIDEO_ID'] . ''); ?>"><i class="dw dw-edit2"></i> Edit</a>
                                                <a class="dropdown-item" href="<?php echo base_url('LandingPageController/deleteVideo/' . $data['VIDEO_ID'] . ''); ?>"><i class="dw dw-eye"></i> Delete</a>
                                            </div>
                                        </div>
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