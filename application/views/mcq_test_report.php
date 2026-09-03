<!DOCTYPE html>
<html>
<?php $this->load->view('includes/header'); ?>

<div class="mobile-menu-overlay"></div>

<style>
    body { font-family: 'Arial', sans-serif; background-color: #eef1f7; color: #333; }
    .container { margin-top: 20px; }
    h1, h3, h4 { color: #FCB22B; text-align: center; }
    .user-details, .chart-container { text-align: center; margin-bottom: 30px; background-color: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); }
    .user-photo { width: 100px; height: 100px; border-radius: 50%; border: 3px solid #FCB22B; margin-bottom: 10px; }
    .report-table { width: 100%; border-collapse: collapse; margin-top: 20px; background-color: #fff; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); }
    .report-table th { background-color: #c1c3cb; color: #fff; padding: 12px; }
    .report-table td { padding: 10px; text-align: center; border: 1px solid #dee2e6; }
    .report-table tr:nth-child(even) { background-color: #f9f9f9; }
    .total-row { font-weight: bold; background-color: #f0f0f0; }
    .info-block { background-color: #fff; padding: 15px; margin: 20px; border-radius: 10px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); text-align: center; }
    .highlight { font-size: 1.2rem; font-weight: bold; color: #FCB22B; margin-top: 10px; }
    .section-spacing { margin-top: 30px; }
</style>
</head>
<body>

<div class="main-container">
    <div class="pd-ltr-20 xs-pd-20-10">
        <div class="min-height-200px">
            <div class="page-header">
                <h4>User Test Response</h4>
            </div>
            <div class="user-details">
                <img src="<?= $user_data['PROFILE_PICTURE'] ?: 'default-user.png' ?>" class="user-photo" alt="User Photo">
                <h3><?= $user_data['NAME'] ?></h3>
                <p><strong>Email:</strong> <?= $user_data['EMAIL'] ?></p>
                <p><strong>Test Name:</strong> <?= $user_data['TEST_NAME'] ?></p>
                <p><strong>Total Questions:</strong> <?= $user_data['TOTAL_QUESTIONS'] ?></p>
                <p><strong>Test Start Date:</strong> <?= $user_data['TEST_START_DATE'] ?></p>
            </div>

            <h3>Responses Calculation</h3>

            <table class="report-table">
                <thead>
                    <tr>
                        <th></th>
                        <th>D</th>
                        <th>I</th>
                        <th>S</th>
                        <th>C</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>MOST</strong></td>
                        <td><?= $most_likely_counts['option_1'] ?></td>
                        <td><?= $most_likely_counts['option_2'] ?></td>
                        <td><?= $most_likely_counts['option_3'] ?></td>
                        <td><?= $most_likely_counts['option_4'] ?></td>
                        <td><?= array_sum($most_likely_counts) ?></td>
                    </tr>
                    <tr>
                        <td><strong>LEAST</strong></td>
                        <td><?= $least_likely_counts['option_1'] ?></td>
                        <td><?= $least_likely_counts['option_2'] ?></td>
                        <td><?= $least_likely_counts['option_3'] ?></td>
                        <td><?= $least_likely_counts['option_4'] ?></td>
                        <td><?= array_sum($least_likely_counts) ?></td>
                    </tr>
                </tbody>
            </table>

            <h4 class="section-spacing">Detailed Report</h4>
            <table class="report-table">
                <thead>
                    <tr>
                        <th>Question No.</th>
                        <th>Most Likely</th>
                        <th>Least Likely</th>
                        <th>Time Spent (seconds)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $total_time_spent = 0; 
                    foreach ($responses as $index => $response): 
                        $total_time_spent += $response['time_spent']; 
                    ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= strtoupper(str_replace('_', ' ', $response['most_likely'])) ?></td>
                        <td><?= strtoupper(str_replace('_', ' ', $response['least_likely'])) ?></td>
                        <td><?= $response['time_spent'] ?> sec</td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="info-block">
                <p class="highlight">Total Responses: <?= count($responses) ?></p>
                <p class="highlight">Total Time Spent: <?= $total_time_spent ?> seconds</p>
            </div>

            <div class="chart-container">
                <img src="https://eyd.franhive.com/uploads/67cb17bf1b8d4_IMG_9303.jpg" alt="Response Analysis" class="full-width-image">
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('includes/footer'); ?>
</body>
</html>
