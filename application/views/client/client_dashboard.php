<!DOCTYPE html>
<html>
<?php $this->load->view('includes/header'); ?>
<div class="mobile-menu-overlay"></div>

<div class="main-container">
    <div class="xs-pd-20-10 pd-ltr-20">
        <div class="title pb-20">
            <h2 class="h3 mb-0">Client Overview</h2>
        </div>
        <div class="row pb-10">
            <div class="col-md-4 mb-20">
                <div class="card-box min-height-200px pd-20 mb-20" data-bgcolor="#455a64">
                    <div class="d-flex justify-content-between pb-20 text-white">
                        <div class="icon h1 text-white">
                            <i class="fa fa-calendar" aria-hidden="true"></i>
                            <!-- <i class="icon-copy fa fa-stethoscope" aria-hidden="true"></i> -->
                        </div>

                    </div>
                    <div class="d-flex justify-content-between align-items-end">
                        <div class="text-white">
                            <div class="font-14">Total Clients</div>
                            <div class="font-24 weight-500">
                                <?= $total_clients ?>
                            </div>
                        </div>
                        <div class="max-width-150">
                            <div id="appointment-chart"></div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-md-8 mb-20">
                <div class="card-box height-100-p pd-20">
                    <div class="d-flex flex-wrap justify-content-between align-items-center pb-0 pb-md-3">
                        <div class="h5 mb-md-0">Recently Added Clients</div>

                    </div>


                    <table class="data-table table stripe hover nowrap">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>CLIENT NAME</th>
                                <th>PHONE</th>
                                <th>CITY</th>
                                <th>STATE</th>
                                <th>DIVISION</th>

                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($all_clients as $client) : ?>
                                <tr>
                                <tr>
                                    </td>
                                    <td><?= $client['ENTITY_ID'] ?></td>
                                    <td><?= $client['NAME'] ?></td>
                                    <td><?= $client['PHONE'] ?></td>
                                    <td><?= $client['CITY'] ?></td>
                                    <td><?= $client['STATE'] ?></td>
                                    <td>
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
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <!-- <div id="activities-chart"></div> -->
                </div>
            </div>

        </div>


    </div>
</div>


</body>
<?php $this->load->view('includes/footer'); ?>

</html>