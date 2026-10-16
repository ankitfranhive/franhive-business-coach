<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color:#333; }
    h3,h4 { text-align:center; color:#FCB22B; margin:8px 0; }
    .box { border:1px solid #ddd; padding:12px; margin-bottom:12px; }
    table { width:100%; border-collapse:collapse; margin-top:8px; }
    th { background:#FCB22B; color:#fff; padding:8px; font-size:11px; }
    td { border:1px solid #ddd; padding:6px; text-align:center; }
    .chart { text-align:center; margin-top:12px; }
    .chart img { max-width:100%; height:auto; }
  </style>
</head>
<body>

  <h3>Test Report — <?= htmlspecialchars($user_data['TEST_NAME'] ?? 'Assessment') ?></h3>

  <div class="box">
    <strong>Name:</strong> <?= htmlspecialchars($user_data['NAME'] ?? '') ?><br>
    <strong>Email:</strong> <?= htmlspecialchars($user_data['EMAIL'] ?? '') ?><br>
    <strong>Test Name:</strong> <?= htmlspecialchars($user_data['TEST_NAME'] ?? '') ?><br>
    <strong>Total Questions:</strong> <?= (int)($user_data['TOTAL_QUESTIONS'] ?? 0) ?><br>
    <strong>Test Start Date:</strong> <?= htmlspecialchars($user_data['TEST_START_DATE'] ?? '') ?><br>
  </div>

  <h4>Responses Calculation</h4>
  <table>
    <thead>
      <tr>
        <th></th><th>D</th><th>I</th><th>S</th><th>C</th><th>Total</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td><strong>MOST</strong></td>
        <td><?= (int)($most_likely_counts['option_1'] ?? 0) ?></td>
        <td><?= (int)($most_likely_counts['option_2'] ?? 0) ?></td>
        <td><?= (int)($most_likely_counts['option_3'] ?? 0) ?></td>
        <td><?= (int)($most_likely_counts['option_4'] ?? 0) ?></td>
        <td><?= array_sum($most_likely_counts) ?></td>
      </tr>
      <tr>
        <td><strong>LEAST</strong></td>
        <td><?= (int)($least_likely_counts['option_1'] ?? 0) ?></td>
        <td><?= (int)($least_likely_counts['option_2'] ?? 0) ?></td>
        <td><?= (int)($least_likely_counts['option_3'] ?? 0) ?></td>
        <td><?= (int)($least_likely_counts['option_4'] ?? 0) ?></td>
        <td><?= array_sum($least_likely_counts) ?></td>
      </tr>
    </tbody>
  </table>

  <h4>Detailed Report</h4>
  <table>
    <thead>
      <tr>
        <th>Q.No</th>
        <th>Most Likely</th>
        <th>Least Likely</th>
        <th>Time (sec)</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $total_time = 0;
      foreach ($responses as $index => $response):
          $total_time += (int)($response['time_spent'] ?? 0);
      ?>
      <tr>
        <td><?= $index + 1 ?></td>
        <td><?= htmlspecialchars(strtoupper(str_replace('_', ' ', $response['most_likely'] ?? ''))) ?></td>
        <td><?= htmlspecialchars(strtoupper(str_replace('_', ' ', $response['least_likely'] ?? ''))) ?></td>
        <td><?= (int)($response['time_spent'] ?? 0) ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <div class="box">
    <strong>Total responses:</strong> <?= count($responses) ?><br>
    <strong>Total time spent:</strong> <?= (int)$total_time ?> seconds
  </div>

  <div class="chart">
    <img src="https://eyd.franhive.com/uploads/67cb17bf1b8d4_IMG_9303.jpg" alt="Response analysis chart">
  </div>

</body>
</html>
