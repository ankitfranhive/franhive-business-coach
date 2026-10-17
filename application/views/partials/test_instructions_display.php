<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$instructions = trim((string)($instructions ?? ''));
if ($instructions === '') {
    return;
}
$is_plain = strip_tags($instructions) === $instructions;
?>
<div class="test-instructions-content">
<?php if ($is_plain): ?>
    <?= nl2br(html_escape($instructions, ENT_QUOTES, 'UTF-8')) ?>
<?php else: ?>
    <?= $instructions ?>
<?php endif; ?>
</div>
