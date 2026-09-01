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
                            <h4>Listing of all Client</h4>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12 text-right">
                        <a class="btn btn-warning" href="<?= base_url('/add-client'); ?>" role="button">
                            Add new Client
                        </a>
                    </div>
                </div>
            </div>
            <!-- Simple Datatable start -->
            <div class="card-box mb-30">
                <?php if ($error = $this->session->flashdata('error')): ?>
                    <div class="pd-20">

                        <div class="alert alert-danger">
                            <?= $error; ?>
                            <?php $this->session->unset_userdata('error'); // Clear the error flashdata 
                            ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($success = $this->session->flashdata('success')): ?>
                    <div class="pd-20">
                        <div class="alert alert-success">
                            <?= $success; ?>
                            <?php $this->session->unset_userdata('success'); // Clear the success flashdata 
                            ?>
                        </div>
                    </div>
                <?php endif; ?>
                <!-- <h4 class="text-blue h4">Listing of all Questions</h4> -->
                <!-- </div> -->
                <div class="pb-20">
                    <table class="data-table table stripe hover nowrap">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>CLIENT NAME</th>
                                <th>PHONE</th>
                                <th>CITY</th>
                                <th>STATE</th>
                                <!-- <th>DIVISION</th> -->

                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($all_clients as $client) : ?>
                               
                                    <td><?= $client['ENTITY_ID'] ?></td>
                                    <td><?= $client['NAME'] ?></td>
                                    <td><?= $client['PHONE'] ?></td>
                                    <td><?= $client['CITY'] ?></td>
                                    <td><?= $client['STATE'] ?></td>
                                    <td class="no-wrap>
                                        <?php
                                        $divisions = explode(',', $client['DIVISION']); // Split the division string into an array
                                        $divisionNames = [];

                                        foreach ($divisions as $division) {
                                            switch ($division) {
                                                case '13814739':
                                                    $divisionNames[] = 'North';
                                                    break;
                                                case '27083643':
                                                    $divisionNames[] = 'South';
                                                    break;
                                                case '28397879':
                                                    $divisionNames[] = 'East';
                                                    break;
                                                case '62312528':
                                                    $divisionNames[] = 'West';
                                                    break;
                                                default:
                                                    $divisionNames[] = 'None'; // If division not found
                                                    break;
                                            }
                                        }

                                        // Join the division names with a comma and print them
                                        echo implode(', ', $divisionNames);
                                        ?>
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                                                <i class="dw dw-more"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                                <a class="dropdown-item" href="<?php echo base_url('ClientController/clientView/' . $client['ENTITY_ID'] . ''); ?>"><i class="dw dw-eye"></i> VIEW</a>
                                                <a class="dropdown-item" href="<?php echo base_url('ClientController/updateClient/' . $client['ENTITY_ID'] . ''); ?>"><i class="dw dw-edit2"></i> Edit</a>
                                                <a class="dropdown-item" href="<?php echo base_url('ClientController/deleteClient/' . $client['ENTITY_ID'] . ''); ?>"><i class="dw dw-eye"></i> Delete</a>
                                                <a class="dropdown-item" href="<?php echo base_url('ClientController/createTask/' . $client['ENTITY_ID'] . ''); ?>"><i class="dw dw-eye"></i> Add Task</a>
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
<script type="text/javascript">
    $(document).ready(function() {
        // Hide the alert after 3 seconds (3000 milliseconds)
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 3000);
    });
</script>

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