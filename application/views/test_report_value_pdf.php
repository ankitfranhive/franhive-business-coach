<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #334155; }
    h2, h3 { color: #0f766e; margin: 8px 0; }
    .box { border: 1px solid #cbd5e1; padding: 12px; margin-bottom: 12px; border-radius: 6px; }
    table { width: 100%; border-collapse: collapse; margin-top: 8px; }
    th { background: #0f766e; color: #fff; padding: 7px; font-size: 10px; }
    td { border: 1px solid #e2e8f0; padding: 7px; vertical-align: top; }
    .stat { display: inline-block; width: 30%; vertical-align: top; margin-right: 2%; }
    .stat strong { font-size: 16px; color: #0f766e; }
  </style>
</head>
<body>

<h2>Value Test Report</h2>

<div class="box">
  <strong>Name:</strong> <?= htmlspecialchars($user_data['NAME'] ?? '') ?><br>
  <strong>Email:</strong> <?= htmlspecialchars($user_data['EMAIL'] ?? '') ?><br>
  <strong>Test:</strong> <?= htmlspecialchars($user_data['TEST_NAME'] ?? '') ?><br>
  <strong>Questions answered:</strong> <?= (int)($attempted ?? 0) ?>
</div>

<?php $counts = $choice_counts ?? ['me' => 0, 'dont_care' => 0, 'not_me' => 0]; ?>

<div class="box">
  <h3>Choice counts</h3>
  <div class="stat"><strong><?= (int)$counts['me'] ?></strong><br>Me (1)</div>
  <div class="stat"><strong><?= (int)$counts['dont_care'] ?></strong><br>I don't care (2)</div>
  <div class="stat"><strong><?= (int)$counts['not_me'] ?></strong><br>Not me (3)</div>
  <p style="margin-top:10px;font-size:10px;">Option 2 is excluded from weighted totals below.</p>
</div>

<div class="box">
  <h3>Weighted totals</h3>
  <p><strong>Me total:</strong> <?= number_format((float)($weighted_me_total ?? 0), 2) ?></p>
  <p><strong>Not me total:</strong> <?= number_format((float)($weighted_not_me_total ?? 0), 2) ?></p>
  <p><strong>Combined (Me + Not me):</strong> <?= number_format((float)($scored_total ?? 0), 2) ?></p>
</div>

<div class="box">
  <h3>Question detail</h3>
  <table>
    <thead>
      <tr>
        <th style="width:5%;">#</th>
        <th>Question</th>
        <th style="width:18%;">Answer</th>
        <th style="width:10%;">Q marks</th>
        <th style="width:10%;">Weighted</th>
      </tr>
    </thead>
    <tbody>
      <?php $i = 1; foreach (($question_analysis ?? []) as $qa): ?>
        <tr>
          <td><?= $i++ ?></td>
          <td><?= htmlspecialchars(strip_tags($qa['question_text'] ?? '')) ?></td>
          <td><?= htmlspecialchars($qa['your_answer'] ?? '') ?></td>
          <td><?= number_format((float)($qa['question_marks'] ?? 0), 2) ?></td>
          <td><?= number_format((float)($qa['weighted_score'] ?? 0), 2) ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

</body>
</html>
