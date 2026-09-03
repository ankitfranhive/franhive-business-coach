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
                            <h4>Listing of all Landing Pages</h4>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12 text-right">
                        <a class="btn btn-warning" href="<?= base_url('/add-landing-page'); ?>" role="button">
                            Add new Landing Page
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

                                <th>LANDING PAGE ID</th>
                                <th>LANDING PAGE TITLE</th>
                                <th>LANDING PAGE URL</th>
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
                            <?php foreach ($all_landing_pages as $landing_page) : ?>

                                <tr>
                                <tr>
                                    </td>
                                    <td><?= $landing_page['LANDING_PAGE_ID'] ?></td>
                                    <td><?= $landing_page['LANDING_PAGE_NAME'] ?></td>
                                    <td>
                                <!-- <?= $landing_page['LANDING_PAGE_URL'] ?> -->
                                <!-- <button class="btn btn-sm btn-light ml-2 copy-url" data-url="<?php echo base_url('LandingPageController/viewLandingPagePreview/' . $landing_page['LANDING_PAGE_ID'] . ''); ?>">
                                    <i class="icon-copy fa fa-copy" aria-hidden="true"></i> Copy URL
                                </button> -->



                                <?php
                                // Convert name to a URL-friendly format
                                $landing_page_slug = preg_replace('/[^A-Za-z0-9-]+/', '-', $landing_page['LANDING_PAGE_NAME']); 
                                $landing_page_slug = trim($landing_page_slug, '-'); // Remove any trailing hyphens
                                $landing_page_url = base_url($landing_page_slug . '/' . $landing_page['LANDING_PAGE_ID']);
                            ?>

                            <button class="btn btn-sm btn-light ml-2 copy-url" data-url="<?php echo $landing_page_url; ?>"> 
                                <i class="icon-copy fa fa-copy" aria-hidden="true"></i> Copy URL
                            </button>

                            </td>
                                    <td class="no-wrap">
                                        <div class="dropdown">
                                            <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                                                <i class="dw dw-more"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                                <a class="dropdown-item" href="<?php echo base_url('LandingPageController/viewLandingPagePreview/' . $landing_page['LANDING_PAGE_ID'] . ''); ?>"><i class="dw dw-eye"></i> PREVIEW</a>
                                                <a class="dropdown-item" href="<?php echo base_url('landing-page-view/' . $landing_page['LANDING_PAGE_ID'] . ''); ?>"><i class="dw dw-eye"></i> VIEW</a>

                                                <a class="dropdown-item" href="<?php echo base_url('landing-page-edit/' . $landing_page['LANDING_PAGE_ID'] . ''); ?>"><i class="dw dw-edit2"></i> Edit</a>
                                                <a class="dropdown-item" href="<?php echo base_url('landing-page-delete/' . $landing_page['LANDING_PAGE_ID'] . ''); ?>"><i class="dw dw-eye"></i> Delete</a>
                                                <!-- <a class="dropdown-item" href="<?php echo base_url('admin/underMaintenance/' . $landing_page['LANDING_PAGE_ID'] . ''); ?>"><i class=""></i> Coupons</a> -->
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
<script>
    // Function to handle copying URL to clipboard
    document.querySelectorAll('.copy-url').forEach(button => {
        button.addEventListener('click', function() {
            // Get the URL from the data-url attribute
            var url = this.getAttribute('data-url');

            // Create a temporary input element
            var tempInput = document.createElement('input');
            tempInput.value = url;
            document.body.appendChild(tempInput);

            // Select the text inside the input element and copy it to clipboard
            tempInput.select();
            tempInput.setSelectionRange(0, 99999); // For mobile devices
            document.execCommand("copy");

            // Remove the temporary input element
            document.body.removeChild(tempInput);

            // Optionally, show a success message (e.g., using an alert or toast)
            alert('URL copied to clipboard: ' + url);
        });
    });
</script>
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