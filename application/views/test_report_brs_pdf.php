<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color:#333; }
    h3,h4 { text-align:center; color:#FCB22B; margin:6px 0; }
    .box { background:#fff; border:1px solid #ddd; padding:12px; margin-bottom:12px; border-radius:6px; }
    table { width:100%; border-collapse:collapse; }
    th { background:#FCB22B; color:#fff; padding:8px; font-size:12px; }
    td { border:1px solid #ddd; padding:8px; vertical-align:top; }
    .ok { color:#2e7d32; font-weight:bold; }
    .bad { color:#c62828; font-weight:bold; }
  </style>
</head>
<body>

  <h3>Test Report</h3>

  <div class="box">
    <strong>Name:</strong> <?= htmlspecialchars($user_data['NAME'] ?? '') ?><br>
    <strong>Email:</strong> <?= htmlspecialchars($user_data['EMAIL'] ?? '') ?><br>
    <strong>Test Name:</strong> <?= htmlspecialchars($user_data['TEST_NAME'] ?? '') ?><br>
    <strong>Total Questions:</strong> <?= (int)($user_data['TOTAL_QUESTIONS'] ?? 0) ?><br>
    <strong>Test Start Date:</strong> <?= htmlspecialchars($user_data['TEST_START_DATE'] ?? '') ?><br>
  </div>

  <div class="box">
    <strong>Attempted:</strong> <?= (int)$attempted ?><br>
    <strong>Total Marks:</strong> <?= (int)$total_marks_gained ?><br>

    <?php if (!empty($show_brs_extras)): ?>
      <strong>User Score (Avg):</strong> <?= htmlspecialchars((string)$user_score) ?><br>
      <strong>Interpretation:</strong> <?= htmlspecialchars((string)$interpretation) ?><br>
    <?php endif; ?>
  </div>

  <div class="box">
    <h4>Question-wise Analysis</h4>
    <table>
      <thead>
        <tr>
          <th style="width:6%;">Q.No</th>
          <th>Question</th>
          <th style="width:20%;">Your Answer</th>
          <th style="width:10%;">Marks</th>
        </tr>
      </thead>
      <tbody>
        <?php $i=1; foreach (($question_analysis ?? []) as $qa): ?>
          <tr>
            <td><?= $i++ ?></td>
            <td><?= nl2br(htmlspecialchars(trim($qa['question_text'] ?? ''))) ?></td>
            <td><?= htmlspecialchars(trim($qa['your_answer'] ?? '')) ?></td>
            <td><?= (int)($qa['marks'] ?? 0) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <?php if (!empty($show_brs_extras)): ?>
    <div class="box">
      <h4>BRS Score Interpretation</h4>
      <table>
        <thead>
          <tr>
            <th>BRS Score</th>
            <th>Interpretation</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach (($brs_table ?? []) as $row): ?>
            <tr>
              <td><?= htmlspecialchars($row['range']) ?></td>
              <td><?= htmlspecialchars($row['label']) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>

</body>
</html>
