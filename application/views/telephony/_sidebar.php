<?php /** @var array $student */ ?>
<div id="ct-sidebar-header">
  Lead
  <button id="ct-sidebar-close" title="Close">✕</button>
</div>
<?php if (!empty($student)): ?>
  <div style="display:grid; gap:8px">
    <div><strong><?= esc($student['name']) ?></strong> (ID: <?= esc($student['id']) ?>)</div>
    <?php if (!empty($student['program'])): ?>
      <div><?= esc($student['program']) ?></div>
    <?php endif; ?>
    <?php if (!empty($student['phone'])): ?>
      <div>📞 <?= esc($student['phone']) ?></div>
    <?php endif; ?>
    <?php if (!empty($student['email'])): ?>
      <div>✉️ <?= esc($student['email']) ?></div>
    <?php endif; ?>
    <?php if (!empty($student['notes_url'])): ?>
      <div><a href="<?= esc($student['notes_url']) ?>" target="_blank" rel="noopener">Open notes</a></div>
    <?php endif; ?>
  </div>
<?php else: ?>
  <div>
    <strong>No match found</strong><br>
    <small>Phone: <?= esc($number ?? '') ?></small><br>
    <?php if (!empty($email)): ?><small>Email: <?= esc($email) ?></small><?php endif; ?>
  </div>
<?php endif; ?>
