<?php
/**
 * Student-facing Value Test results table ("Next Door Inventory Results Evaluation").
 * Expects: $result_rows (from value_test_student_result_rows()), $student_name, $result_date.
 * Inline styles only so the same markup renders in the browser and in Dompdf.
 */
$result_rows = isset($result_rows) && is_array($result_rows) ? $result_rows : [];
$cell = 'border:1px solid #000; padding:4px 8px; font-size:14px; height:34px;';
?>
<div style="font-family: Arial, Helvetica, sans-serif; color:#222;">
    <h1 style="font-size:30px; line-height:1.2; margin:0 0 22px; font-weight:400;">
        <strong>The Next Door Inventory</strong> Results Evaluation:
    </h1>
    <p style="font-weight:700; font-size:14px; margin:0 0 10px;">DATE: <span style="font-weight:400;"><?= htmlspecialchars((string)($result_date ?? '')) ?></span></p>
    <p style="font-weight:700; font-size:14px; margin:0 0 28px;">NAME: <span style="font-weight:400;"><?= htmlspecialchars((string)($student_name ?? '')) ?></span></p>

    <?php if (empty($result_rows)): ?>
        <p style="color:#666;">Results are not available for this test yet. Please contact your coach.</p>
    <?php else: ?>
    <table style="border-collapse:collapse; width:100%; max-width:480px; margin:0 auto;">
        <thead>
            <tr>
                <th style="<?= $cell ?> width:34%; text-align:center; font-weight:400;">LEVEL</th>
                <th style="<?= $cell ?> width:33%; text-align:center; font-weight:400;">Me!</th>
                <th style="<?= $cell ?> width:33%; text-align:center; font-weight:400;">Not Me!</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($result_rows as $r): ?>
                <tr>
                    <td style="<?= $cell ?> text-align:right;"><?= htmlspecialchars($r['label']) ?></td>
                    <td style="<?= $cell ?> text-align:center;"><?= (int)$r['me'] ?></td>
                    <td style="<?= $cell ?> text-align:center;"><?= (int)$r['not_me'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
</div>
